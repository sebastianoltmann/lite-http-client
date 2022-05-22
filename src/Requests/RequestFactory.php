<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests;

use LiteHttpClient\Exceptions\InvalidRequestTypeException;
use LiteHttpClient\Requests\Types\{DeleteRequest, GetRequest, PatchRequest, PostRequest, PutRequest};
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface as PSRRequestFactoryInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * @throws InvalidRequestTypeException
     */
    public static function make(
        string $method,
        UriInterface $uri,
        PSRRequestFactoryInterface $factory,
        ClientInterface $client
    ): RequestInterface {
        switch ($method) {
            case HttpRequestType::POST:
                return new PostRequest($uri, $factory, $client);
            case HttpRequestType::GET:
                return new GetRequest($uri, $factory, $client);
            case HttpRequestType::PUT:
                return new PutRequest($uri, $factory, $client);
            case HttpRequestType::PATCH:
                return new PatchRequest($uri, $factory, $client);
            case HttpRequestType::DELETE:
                return new DeleteRequest($uri, $factory, $client);
            default:
                throw new InvalidRequestTypeException('Invalid method type.');
        }
    }
}
