<?php


namespace Freezemage\LookupBot\Documentation\Entry;

use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Entry;


class ClassEntry implements Entry
{
    public function compile(Compiler $compiler): string
    {
        return $compiler->compileClass($this);
    }
}
