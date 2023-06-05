<?php


namespace Freezemage\LookupBot\Documentation\Compiler;

use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Entry\ClassEntry;
use Freezemage\LookupBot\Documentation\Entry\Extension;
use Freezemage\LookupBot\Documentation\Entry\Method;
use Freezemage\LookupBot\ValueObject\Parameter;


class Descriptive implements Compiler
{
    public function compileMethod(Method $method): string
    {
        $synopsis = array_map(
                static fn (string $synopsis): string => "```php\n{$synopsis}```",
                $method->synopsis
        );
        $synopsis = implode("\n", $synopsis);

        $parameters = array_map(
                static fn (Parameter $parameter): string => "> - `{$parameter->name}` - {$parameter->description}",
                $method->parameters
        );
        $parameters = implode("\n", $parameters);

        return <<<METHOD
        ### Synopsis
        $synopsis
        ### Parameters
        $parameters
        ### Return values
        > $method->returnValue
        ### Errors
        > $method->errors
        METHOD;
    }

    public function compileClass(ClassEntry $classEntry): string
    {
        // TODO: Implement compileClass() method.
    }

    public function compileExtension(Extension $extension): string
    {
        // TODO: Implement compileExtension() method.
    }
}
