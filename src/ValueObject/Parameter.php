<?php


namespace Freezemage\LookupBot\ValueObject;


final class Parameter
{
    public function __construct(
            public readonly string $name,
            public readonly string $description
    ) {
    }

}
