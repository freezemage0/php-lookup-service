<?php


namespace Freezemage\LookupBot;

use Freezemage\LookupBot\TextFormat\Node;


final class FormattedText implements Node
{
    private array $nodes = [];

    public function append(Node $node): void
    {
        $this->nodes[] = $node;
    }

    public function format(): string
    {
        $text = array_map(static fn (Node $node): string => $node->format(), $this->nodes);

        return implode('', $text);
    }
}
