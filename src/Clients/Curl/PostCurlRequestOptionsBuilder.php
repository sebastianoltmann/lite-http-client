<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use Psr\Http\Message\RequestInterface as PSRRequestInterface;

class PostCurlRequestOptionsBuilder extends AbstractCurlRequestOptionsBuilder
{
    protected function prepareMethod(CurlClientInterface $client, PSRRequestInterface $request): CurlClientInterface
    {
        $body = $request->getBody();
        $bodySize = $body->getSize();

        if ($bodySize === 0) {
            return $client;
        }

        if ($body->isSeekable()) {
            $body->rewind();
        }

        if (null === $bodySize || $bodySize > 1024 * 1024) {
            $client->setOption(CURLOPT_UPLOAD, true);

            if (null !== $bodySize) {
                $client->setOption(CURLOPT_INFILESIZE, $bodySize);
            }

            $client->setOption(CURLOPT_READFUNCTION, function ($ch, $fd, $length) use ($body) {
                return $body->read($length);
            });
        } else {
            $client->setOption(CURLOPT_POSTFIELDS, (string)$body);
        }

        return $client;
    }
}
