<?php


namespace Freezemage\LookupBot;


use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\ParserStrategy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;


class Service
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

    public function findByDefinition(string $definition, Compiler $compiler): string
    {
        $definition = Service::sanitize($definition);

        $request = $this->requestFactory->createRequest('GET', "https://www.php.net/{$definition}");
        $response = $this->client->sendRequest($request);
        $crawler = new Crawler($response->getBody());

        $selector = str_replace('.', '\\.', $definition);

        foreach ($this->parsers as $parser) {
            if (!$parser->isProcessable($crawler, $selector)) {
                continue;
            }

            $entry = $parser->parse($crawler);
            return $entry->compile($compiler);
        }

        throw new RuntimeException('Failed to find by definition.');
    }

    private static function sanitize(string $definition): string
    {
        $definition = preg_replace('/[^A-Za-z0-9:\->_.]/', '', $definition);
        return str_replace(['::', '->'], '.', $definition);
    }
}
