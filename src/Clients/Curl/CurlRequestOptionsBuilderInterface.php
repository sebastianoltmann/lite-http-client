<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use LiteHttpClient\Clients\ClientInterface;
use Psr\Http\Message\RequestInterface as PSRRequestInterface;

interface CurlRequestOptionsBuilderInterface
{
    public function prepare(ClientInterface $client, PSRRequestInterface $request): ClientInterface;
}
