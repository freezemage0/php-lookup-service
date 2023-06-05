<?php


namespace Freezemage\LookupBot;


use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Compiler\Descriptive;
use Freezemage\LookupBot\Documentation\Compiler\SynopsisOnly;


class Bot
{
    /**
     * @param Service $service
     * @param array<array-key, Compiler> $compilers
     */
    public function __construct(
            private readonly Service $service,
            private array $compilers = []
    ) {
        $this->appendDefaultCompilers();
    }

    public function run(Message $message, array $arguments): void
    {
        $query = array_shift($arguments);

        while (!empty($arguments)) {
            $argument = array_shift($arguments);

            $compiler = $this->resolveCompiler($argument);
        }

        $result = $this->service->findByDefinition($query, $compiler ?? new Descriptive());

        $reply = MessageBuilder::new()->setContent($result);
        $message->reply($reply);
    }

    private function appendDefaultCompilers(): void
    {
        $this->compilers = [
                ...$this->compilers,
                new Descriptive(),
                new SynopsisOnly()
        ];
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
