<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use Psr\Http\Message\RequestInterface as PSRRequestInterface;

class GetCurlRequestOptionsBuilder extends AbstractCurlRequestOptionsBuilder
{
    protected function prepareMethod(CurlClientInterface $client, PSRRequestInterface $request): CurlClientInterface
    {
        $client->setOption(CURLOPT_HTTPGET, true);

        return $client;
    }
}
