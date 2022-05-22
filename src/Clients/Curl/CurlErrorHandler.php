<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use LiteHttpClient\Exceptions\ErrorCallbackException;
use LiteHttpClient\Exceptions\ErrorNetworkException;
use LiteHttpClient\Exceptions\ErrorRequestException;
use Psr\Http\Message\RequestInterface;

final class CurlErrorHandler
{
    /**
     * @throws ErrorCallbackException
     * @throws ErrorNetworkException
     * @throws ErrorRequestException
     */
    public function handle(RequestInterface $request, int $errno)
    {
        switch ($errno) {
            case CURLE_OK:
                break;
            case CURLE_COULDNT_RESOLVE_PROXY:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_COULDNT_CONNECT:
            case CURLE_OPERATION_TIMEOUTED:
            case CURLE_SSL_CONNECT_ERROR:
                throw new ErrorNetworkException($request, $errno, $errno);
            case CURLE_ABORTED_BY_CALLBACK:
                throw new ErrorCallbackException($request, $errno, $errno);
            default:
                throw new ErrorRequestException($request, $errno, $errno);
        }
    }
}
