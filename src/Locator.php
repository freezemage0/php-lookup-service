<?php


namespace Freezemage\LookupBot;


use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Entry;
use Freezemage\LookupBot\Documentation\ParserStrategy;
use Freezemage\LookupBot\ValueObject\Definition;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;


class Locator
{
    /**
     * @param RequestFactoryInterface $requestFactory
     * @param ClientInterface $client
     * @param ParserStrategy[] $parsers
     */
    public function __construct(
            private readonly RequestFactoryInterface $requestFactory,
            private readonly ClientInterface $client,
            private readonly array $parsers
    ) {
    }

    public function find(Definition $definition): Entry
    {
        $request = $this->requestFactory->createRequest(
                'GET',
                "https://www.php.net/{$definition->language->value}/{$definition->query}"
        );

        $response = $this->client->sendRequest($request);
        $crawler = new Crawler($response->getBody());

        foreach ($this->parsers as $parser) {
            if (!$parser->isProcessable($crawler, $definition->selector)) {
                continue;
            }

            return $parser->parse($crawler);
        }

        throw new RuntimeException('Failed to find by definition.');
    }
}
