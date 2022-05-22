<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface RequestInterface
{
    public function __construct(
        UriInterface $uri,
        RequestFactoryInterface $factory,
        ClientInterface $client
    );

    public function send(array $body, array $headers, array $query): ResponseInterface;

    public function method(): string;
}
