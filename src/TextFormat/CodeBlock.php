<?php


namespace Freezemage\LookupBot\TextFormat;

final class CodeBlock implements Node
{

    public function __construct(private readonly string $value)
    {
    }


    public function format(): string
    {
        return <<<CODEBLOCK
        ```php
        $this->value
        ```
        CODEBLOCK;
    }
}
