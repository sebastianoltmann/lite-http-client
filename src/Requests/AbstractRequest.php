<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests;

use LiteHttpClient\Clients\ClientInterface;
use LiteHttpClient\Requests\Headers\HeadersCollection;
use LiteHttpClient\Requests\Utils\StringBodyBuilder;
use LiteHttpClient\Requests\Utils\StringQueryBuilder;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface as PSRRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

abstract class AbstractRequest implements RequestInterface
{
    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var RequestFactoryInterface
     */
    protected $factory;

    /**
     * @var ClientInterface
     */
    protected $client;

    public function __construct(
        UriInterface $uri,
        RequestFactoryInterface $factory,
        ClientInterface $client
    ) {
        $this->uri = $uri;
        $this->factory = $factory;
        $this->client = $client;
    }

    public function send(array $body, array $headers, array $query): ResponseInterface
    {
        $uri = $this->uri->withQuery(
            (new StringQueryBuilder($this->uri))->build($query)
        );

        $request = $this->factory->createRequest($this->method(), $uri);

        $request->getBody()->write(
            (new StringBodyBuilder())->build($body)
        );

        $request = $this->addHeaders($request, new HeadersCollection($headers));

        return $this->client->sendRequest($request);
    }

    private function addHeaders(PSRRequestInterface $request, HeadersCollection $headers): PSRRequestInterface
    {
        $headers->each(function ($name, $value) use (&$request) {
            $request = $request->withAddedHeader($name, $value);
        });

        return $request;
    }
}
