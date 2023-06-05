<?php


namespace Freezemage\LookupBot;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Freezemage\LookupBot\Documentation\Compiler\Descriptive;
use Freezemage\LookupBot\Documentation\Compiler\SynopsisOnly;


class Bot
{
    public function __construct(private readonly Service $service)
    {
    }

    public function run(Message $message, array $arguments): void
    {
        $query = array_shift($arguments);

        if (in_array('--short', $arguments)) {
            $compiler = new SynopsisOnly();
        } else {
            $compiler = new Descriptive();
        }

        $query = preg_replace('/[^A-Za-z0-9:\->_.]/', '', $query);
        $query = str_replace(['::', '->'], '.', $query);

        $result = $this->service->findByDefinition($query, $compiler);

        $reply = MessageBuilder::new()->setContent($result);
        $message->reply($reply);
    }
}
