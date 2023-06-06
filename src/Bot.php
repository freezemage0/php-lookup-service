<?php


namespace Freezemage\LookupBot;


use Exception;
use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Compiler\Descriptive;
use Freezemage\LookupBot\Documentation\Compiler\Fallback;
use Freezemage\LookupBot\Documentation\CompilerResolverInterface;
use Freezemage\LookupBot\Documentation\DefaultCompilerResolver;
use Freezemage\LookupBot\Documentation\ParserStrategy;
use Freezemage\LookupBot\ValueObject\Language;
use Ragnarok\Fenrir\Discord;
use Ragnarok\Fenrir\Gateway\Events\MessageCreate;
use Ragnarok\Fenrir\Rest\Helpers\Channel\MessageBuilder;


final class Bot
{
    const COMMAND_PREFIX = '!pls';

    /**
     * @param Locator $locator
     * @param array<array-key, Compiler> $compilers
     * @param Compiler $defaultCompiler
     * @param DefinitionFactory $definitionFactory
     */
    public function __construct(
            private readonly Discord $facade,
            private readonly Locator $locator,
            private readonly DefinitionFactory $definitionFactory = new DefinitionFactory(),
            private array $compilers = [],
            private readonly Compiler $defaultCompiler = new Descriptive()
    ) {
    }

    public function run(MessageCreate $message): void
    {
        if (!empty($message->author->bot)) {
            return;
        }

        $arguments = explode(' ', $message->content);

        $command = array_shift($arguments);
        if ($command !== Bot::COMMAND_PREFIX) {
            return;
        }

        $query = array_shift($arguments);

        while (!empty($arguments)) {
            $argument = array_shift($arguments);

            $compiler ??= $this->resolveCompiler($argument);
            $language ??= Language::match($argument);
        }

        try {
            $definition = $this->definitionFactory->create($query, $language ?? Language::ENGLISH);
            $entry = $this->locator->find($definition);
            $content = $entry->compile($compiler ?? $this->defaultCompiler);
        } catch (Exception $e) {
            // todo: actually handle an exception.
            $content = 'Unable to process your query, sorry :sweat_smile:';
        }

        $builder = new MessageBuilder();
        $builder->setContent($content)
                ->setReference(
                        $message->channel_id,
                        $message->id,
                        true
                );

        $this->facade->rest->channel->createMessage($message->channel_id, $builder);
    }

    public function addCompiler(Compiler $compiler): void
    {
        $this->compilers[] = $compiler;
    }

    private function resolveCompiler(string $argument): ?Compiler
    {
        foreach ($this->compilers as $compiler) {
            if (in_array($argument, $compiler->getArgumentAliases())) {
                return $compiler;
            }
        }

        return null;
    }
}
