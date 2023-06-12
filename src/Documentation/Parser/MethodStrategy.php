<?php


namespace Freezemage\LookupBot\Documentation\Parser;


use DOMNode;
use DOMText;
use Freezemage\LookupBot\Documentation\Entry;
use Freezemage\LookupBot\Documentation\Entry\Method;
use Freezemage\LookupBot\Documentation\Entry\Method\ReturnValues;
use Freezemage\LookupBot\Documentation\ParserStrategy;
use Freezemage\LookupBot\FormattedText;
use Freezemage\LookupBot\TextFormat\InlineCodeBlock;
use Freezemage\LookupBot\TextFormat\Node;
use Freezemage\LookupBot\TextFormat\Plain;
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
        $synopsis = $page
                ->filter('.description .methodsynopsis')
                ->each(static fn(Crawler $synopsis): string => $synopsis->text());

        $parameters = $page
                ->filter('.parameters')
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

        $returnValues = $this->parseReturnValue($page);

        $errors = $page->filter('.errors p')->text();

        return new Method($synopsis, $parameters, $returnValues, $errors);
    }

    private function parseReturnValue(Crawler $page): ?ReturnValues
    {
        $title = $page->filter('.returnvalues .title')->text();

        $description = new FormattedText();
        $paragraphs = $page->filter('.returnvalues .para')->each($this->parseParagraph(...));
        var_dump($paragraphs);

        foreach ($paragraphs as $paragraph) {
            foreach ($paragraph as $node) {
                $description->append($node);
            }
        }

        return new ReturnValues($title, $description);
    }

    private function parseParagraph(Crawler $paragraph): array
    {
        return array_map(
                static function (DOMNode $text): Node {
                    if ($text instanceof DOMText) {
                        return new Plain($text->textContent);
                    }

                    $text = new Crawler($text);
                    $value = trim($text->text());

                    $codeSelectors = ['code', '.function', '.classname'];

                    foreach ($codeSelectors as $selector) {
                        $element = $text->filter($selector);
                        if ($element->count() > 0) {
                            return new InlineCodeBlock($element->text());
                        }
                    }

                    return new Plain($value);
                },
                [...$paragraph->getNode(0)->childNodes]
        );
    }
}
