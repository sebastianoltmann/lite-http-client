<?php

declare(strict_types=1);

namespace LiteHttpClient;

use Http\Message\ResponseFactory;
use LiteHttpClient\Clients\Curl\CurlClient;
use LiteHttpClient\Exceptions\InvalidClientException;
use LiteHttpClient\Exceptions\InvalidRequestTypeException;
use LiteHttpClient\Requests\HttpRequestType;
use LiteHttpClient\Requests\RequestFactory;
use LiteHttpClient\Requests\RequestInterface;
use LiteHttpClient\Requests\Utils\UriBuilder;
use LiteHttpClient\Requests\Utils\UriBuilderInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class HttpClient implements HttpClientInterface
{
    /**
     * @var UriInterface|null
     */
    private $baseUri;

    public function __construct(?string $baseUri = null)
    {
        if ($baseUri !== null) {
            $this->baseUri = $this->uriBuilder()->prepare($baseUri);
        }
    }

    protected function uriBuilder(): UriBuilderInterface
    {
        return new UriBuilder();
    }

    /**
     * @return ResponseFactoryInterface|ResponseFactory
     */
    protected function responseFactory()
    {
        return new Psr17Factory();
    }

    /**
     * @throws InvalidClientException
     * @throws InvalidRequestTypeException
     */
    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface
    {
        return $this->prepare(HttpRequestType::GET, $uri)
            ->send([], $headers, $query);
    }

    /**
     * @throws InvalidClientException
     * @throws InvalidRequestTypeException
     */
    public function post(string $uri, array $body = [], array $headers = [], array $query = []): ResponseInterface
    {
        return $this->prepare(HttpRequestType::POST, $uri)
            ->send($body, $headers, $query);
    }

    /**
     * @throws InvalidClientException
     * @throws InvalidRequestTypeException
     */
    public function patch(string $uri, array $body = [], array $headers = [], array $query = []): ResponseInterface
    {
        return $this->prepare(HttpRequestType::PATCH, $uri)
            ->send($body, $headers, $query);
    }

    /**
     * @throws InvalidClientException
     * @throws InvalidRequestTypeException
     */
    public function put(string $uri, array $body = [], array $headers = [], array $query = []): ResponseInterface
    {
        return $this->prepare(HttpRequestType::PUT, $uri)
            ->send($body, $headers, $query);
    }

    /**
     * @throws InvalidClientException
     * @throws InvalidRequestTypeException
     */
    public function delete(string $uri, array $headers = []): ResponseInterface
    {
        return $this->prepare(HttpRequestType::DELETE, $uri)
            ->send([], $headers, []);
    }

    /**
     * @throws Exceptions\InvalidClientException
     * @throws InvalidRequestTypeException
     */
    protected function prepare(string $method, string $uri): RequestInterface
    {
        return RequestFactory::make($method,
            $this->uriBuilder()->prepare($uri, $this->baseUri),
            new Psr17Factory(),
            new CurlClient($this->responseFactory())
        );
    }
}
