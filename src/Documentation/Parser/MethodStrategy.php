<?php


namespace Freezemage\LookupBot\Documentation\Parser;


use Freezemage\LookupBot\Documentation\Entry;
use Freezemage\LookupBot\Documentation\Entry\Method;
use Freezemage\LookupBot\Documentation\ParserStrategy;
use Freezemage\LookupBot\ValueObject\Parameter;
use Symfony\Component\DomCrawler\Crawler;


class MethodStrategy implements ParserStrategy
{

    public function isProcessable(Crawler $page, string $selector): bool
    {
        return $page->filter("#{$selector}")->count() !== 0;
    }

    public function parse(Crawler $page): ?Entry
    {
        $synopsis = $page->filter('.description .methodsynopsis')
                ->each(static fn(Crawler $synopsis): string => $synopsis->text());

        $parameters = $page->filter('.parameters')
                ->each(static function (Crawler $parameter): array {
                    $name = $parameter->filter('dt .parameter')->each(
                            static fn(Crawler $name): string => $name->text()
                    );
                    $description = $parameter->filter('dd')->each(
                            static fn(Crawler $description): string => $description->text()
                    );

                    return array_map(
                            static fn(string $name, string $description): Parameter => new Parameter(
                                    $name, $description
                            ),
                            $name,
                            $description
                    );
                });

        $parameters = array_merge(...$parameters);

        $returnValues = $page->filter('.returnvalues p')->text();

        $errors = $page->filter('.errors p')->text();

        return new Method($synopsis, $parameters, $returnValues, $errors);
    }
}
