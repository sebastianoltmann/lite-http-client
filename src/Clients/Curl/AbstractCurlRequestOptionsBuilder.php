<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use Psr\Http\Message\RequestInterface as PSRRequestInterface;
use UnexpectedValueException;

abstract class AbstractCurlRequestOptionsBuilder implements CurlRequestOptionsBuilderInterface
{
    abstract protected function prepareMethod(
        CurlClientInterface $client,
        PSRRequestInterface $request
    ): CurlClientInterface;

    public function prepare(CurlClientInterface $client, PSRRequestInterface $request): CurlClientInterface
    {
        $client->setOption(CURLOPT_CUSTOMREQUEST, $request->getMethod());
        $client->setOption(CURLOPT_URL, $request->getUri()->__toString());

        $headersConverter = new CurlHeadersConverter();
        $client->setOption(CURLOPT_HTTPHEADER, $headersConverter->convert($request->getHeaders()));

        if (0 !== $version = $this->getProtocolVersion($request)) {
            $client->setOption(CURLOPT_HTTP_VERSION, $version);
        }

        if ($request->getUri()->getUserInfo()) {
            $client->setOption(CURLOPT_USERPWD, $request->getUri()->getUserInfo());
        }

        return $this->prepareMethod($client, $request);
    }

    private function getProtocolVersion(PSRRequestInterface $request): int
    {
        switch ($request->getProtocolVersion()) {
            case '1.0':
                return CURL_HTTP_VERSION_1_0;
            case '1.1':
                return CURL_HTTP_VERSION_1_1;
            case '2.0':
                if (\defined('CURL_HTTP_VERSION_2_0')) {
                    return CURL_HTTP_VERSION_2_0;
                }

                throw new UnexpectedValueException('libcurl 7.33 needed for HTTP 2.0 support');
            default:
                return 0;
        }
    }
}
