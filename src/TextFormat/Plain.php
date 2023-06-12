<?php


namespace Freezemage\LookupBot\TextFormat;

final class Plain implements Node
{
    public function __construct(private readonly string $value)
    {
    }

    public function format(): string
    {
        return preg_replace('/\s+/', ' ', $this->value);
    }
}
