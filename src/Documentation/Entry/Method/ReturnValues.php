<?php


namespace Freezemage\LookupBot\Documentation\Entry\Method;


use Freezemage\LookupBot\FormattedText;


class ReturnValues
{
    public function __construct(
            public readonly string $title,
            public readonly FormattedText $description
    ) {
    }
}
