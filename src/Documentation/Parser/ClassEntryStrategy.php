<?php


namespace Freezemage\LookupBot\Documentation\Parser;

use Freezemage\LookupBot\Documentation\Entry;
use Freezemage\LookupBot\Documentation\ParserStrategy;
use Symfony\Component\DomCrawler\Crawler;


final class ClassEntryStrategy implements ParserStrategy
{

    public function isProcessable(Crawler $page, string $selector): bool
    {
        return $page->filter("#class\\.{$selector}")->count() !== 0;
    }

    public function parse(Crawler $page): ?Entry
    {

    }
}
