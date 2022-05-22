<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use LiteHttpClient\Clients\ClientInterface;
use Psr\Http\Message\RequestInterface as PSRRequestInterface;

class GetCurlRequestOptionsBuilder extends AbstractCurlRequestOptionsBuilder
{
    protected function prepareMethod(ClientInterface $client, PSRRequestInterface $request): ClientInterface
    {
        $client->setOption(CURLOPT_HTTPGET, true);

        return $client;
    }
}
