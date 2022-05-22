<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use Psr\Http\Message\RequestInterface as PSRRequestInterface;

interface CurlRequestOptionsBuilderInterface
{
    public function prepare(CurlClientInterface $client, PSRRequestInterface $request): CurlClientInterface;
}
