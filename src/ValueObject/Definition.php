<?php


namespace Freezemage\LookupBot\ValueObject;


final class Definition
{
    public readonly string $query;
    public readonly Language $language;
    public readonly string $selector;

    public function __construct(string $query, Language $language, string $selector)
    {
        $this->query = $query;
        $this->language = $language;
        $this->selector = $selector;
    }
}
