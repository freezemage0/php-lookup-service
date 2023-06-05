<?php


namespace Freezemage\LookupBot\Documentation;


use Freezemage\LookupBot\Documentation\Entry\ClassEntry;
use Freezemage\LookupBot\Documentation\Entry\Extension;
use Freezemage\LookupBot\Documentation\Entry\Method;


interface Compiler
{
    public function getArgumentAliases(): array;

    public function compileMethod(Method $method): string;

    public function compileClass(ClassEntry $classEntry): string;

    public function compileExtension(Extension $extension): string;
}
