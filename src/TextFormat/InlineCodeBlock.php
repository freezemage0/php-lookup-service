<?php


namespace Freezemage\LookupBot\TextFormat;

class InlineCodeBlock implements Node
{

    public function __construct(private readonly string $text)
    {
    }

    public function format(): string
    {
        return "`{$this->text}`";
    }
}
