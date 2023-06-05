<?php


namespace Freezemage\LookupBot;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Freezemage\LookupBot\Documentation\Compiler\Descriptive;


class Bot
{
    public function __construct(private readonly Service $service)
    {
    }

    public function run(Message $message, array $arguments): void
    {
        $query = array_shift($arguments);

        $query = preg_replace('/[^A-Za-z0-9:\->_.]/', '', $query);
        $query = str_replace(['::', '->'], '.', $query);

        var_dump($query);

        $compiler = new Descriptive();
        $result = $this->service->findByDefinition($query, $compiler);

        $reply = MessageBuilder::new()->setContent($result);
        $message->reply($reply);
    }
}
