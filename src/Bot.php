<?php


namespace Freezemage\LookupBot;


use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Request\Option;
use Discord\Parts\Interactions\Interaction;
use Exception;
use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Compiler\Descriptive;
use Freezemage\LookupBot\Documentation\Compiler\SynopsisOnly;
use Freezemage\LookupBot\ValueObject\Language;


final class Bot
{
    /**
     * @param Locator $locator
     * @param array<array-key, Compiler> $compilers
     * @param Compiler $defaultCompiler
     * @param DefinitionFactory $definitionFactory
     */
    public function __construct(
            private readonly Locator $locator,
            private array $compilers = [],
            private readonly Compiler $defaultCompiler = new Descriptive(),
            private readonly DefinitionFactory $definitionFactory = new DefinitionFactory()
    ) {
        $this->appendDefaultCompilers();
    }

    /**
     * TODO: Replace with injectable CompilerCollection.
     */
    private function appendDefaultCompilers(): void
    {
        $this->compilers = [
                ...$this->compilers,
                new Descriptive(),
                new SynopsisOnly()
        ];
    }

    public function run(Interaction $interaction): void
    {
        $arguments = array_map(
                static fn (Option $o): string => $o->value,
                $interaction->data->options->toArray()
        );

        $query = array_shift($arguments);

        while (!empty($arguments)) {
            $argument = array_shift($arguments);

            $compiler ??= $this->resolveCompiler($argument);
            $language ??= Language::match($argument);
        }

        try {
            $content = $this->locator->find(
                    $this->definitionFactory->create($query, $language ?? Language::ENGLISH),
                    $compiler ?? $this->defaultCompiler
            );
        } catch (Exception $e) {
            $content = 'Unable to process your query, sorry :sweat_smile:';
        }

        $reply = MessageBuilder::new()->setContent($content);
        $interaction->respondWithMessage($reply);
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
