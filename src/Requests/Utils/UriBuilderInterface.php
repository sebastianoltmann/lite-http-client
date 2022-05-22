<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests\Utils;

use Psr\Http\Message\UriInterface;

interface UriBuilderInterface
{
    public function prepare(string $uri, ?UriInterface $baseUri = null): UriInterface;
}
