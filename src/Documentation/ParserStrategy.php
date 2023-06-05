<?php


namespace Freezemage\LookupBot\Documentation;

use Freezemage\LookupBot\ValueObject\Result;
use Symfony\Component\DomCrawler\Crawler;


interface ParserStrategy
{
    public function isProcessable(Crawler $page, string $query): bool;

    public function parse(Crawler $page): ?Entry;
}
