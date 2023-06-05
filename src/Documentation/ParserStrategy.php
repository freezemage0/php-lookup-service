<?php


namespace Freezemage\LookupBot\Documentation;


use Symfony\Component\DomCrawler\Crawler;


interface ParserStrategy
{
    public function isProcessable(Crawler $page, string $selector): bool;

    public function parse(Crawler $page): ?Entry;
}
