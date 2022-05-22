<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface as PSRRequestFactoryInterface;
use Psr\Http\Message\UriInterface;

interface RequestFactoryInterface
{
    public static function make(
        string $method,
        UriInterface $uri,
        PSRRequestFactoryInterface $factory,
        ClientInterface $client
    ): RequestInterface;
}
