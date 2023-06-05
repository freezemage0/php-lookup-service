<?php


namespace Freezemage\LookupBot\Documentation\Entry;

use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Entry;


class Extension implements Entry
{

    public function compile(Compiler $compiler): string
    {
        return $compiler->compileExtension($this);
    }
}
