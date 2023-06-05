<?php


namespace Freezemage\LookupBot\Documentation\Compiler;


use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Entry\ClassEntry;
use Freezemage\LookupBot\Documentation\Entry\Extension;
use Freezemage\LookupBot\Documentation\Entry\Method;


class SynopsisOnly implements Compiler
{
    public function compileMethod(Method $method): string
    {
        $synopsis = array_map(
                static fn(string $synopsis): string => "```php\n{$synopsis}\n```",
                $method->synopsis
        );

        return implode("\n", $synopsis);
    }

    public function compileClass(ClassEntry $classEntry): string
    {
        // TODO: Implement compileClass() method.
    }

    public function compileExtension(Extension $extension): string
    {
        // TODO: Implement compileExtension() method.
    }

    public function getArgumentAliases(): array
    {
        return ['--short', '-s'];
    }
}
