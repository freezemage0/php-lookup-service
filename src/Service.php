<?php


namespace Freezemage\LookupBot;

use Freezemage\LookupBot\Documentation\Compiler;
use Freezemage\LookupBot\Documentation\Compiler\Descriptive;
use Freezemage\LookupBot\Documentation\ParserStrategy;
use Freezemage\LookupBot\ValueObject\Result;
use Freezemage\LookupBot\ValueObject\ReturnValue;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;


class Service
{
    /**
     * @param RequestFactoryInterface $requestFactory
     * @param ClientInterface $client
     *
     * @param ParserStrategy[] $parsers
     */

    public function __construct(
            private readonly RequestFactoryInterface $requestFactory,
            private readonly ClientInterface $client,
            private readonly array $parsers
    ) {
    }

    public function findByDefinition(string $definition, Compiler $compiler = null): string
    {
        $compiler ??= new Descriptive();

        $request = $this->requestFactory->createRequest('GET', "https://www.php.net/{$definition}");
        $response = $this->client->sendRequest($request);

        $crawler = new Crawler($response->getBody());
        $definition = str_replace('.', '\\.', $definition);

        foreach ($this->parsers as $parser) {
            if (!$parser->isProcessable($crawler, $definition)) {
                continue;
            }

            $entry = $parser->parse($crawler);
            return $entry->compile($compiler);
        }

        throw new RuntimeException('Failed to find by definition.');
    }
}
