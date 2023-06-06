<?php


namespace Freezemage\LookupBot;

use Freezemage\LookupBot\ValueObject\Definition;
use Freezemage\LookupBot\ValueObject\Language;


class DefinitionFactory
{
    public function create(string $query, Language $language): Definition
    {
        $query = preg_replace('/[^A-Za-z0-9:\->_.]/', '', $query);
        $query = str_replace(['::', '->'], '.', $query);

        return new Definition(
                $query,
                $language,
                str_replace('.', '\\.', $query)
        );
    }
}
