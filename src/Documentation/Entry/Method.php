<?php


namespace Freezemage\LookupBot\Documentation\Entry;


use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Entry;


final class Method implements Entry
{

    public function __construct(
            public readonly array $synopsis,
            public readonly array $parameters,
            public readonly string $returnValue,
            public readonly string $errors
    ) {
    }

    public function compile(Compiler $compiler): string
    {
        return $compiler->compileMethod($this);
    }
}
