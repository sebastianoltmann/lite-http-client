<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use LiteHttpClient\Exceptions\InvalidRequestTypeException;
use LiteHttpClient\Requests\HttpRequestType;
use Psr\Http\Message\RequestInterface as PSRRequestInterface;

class CurlRequestOptionsBuilderFactory
{
    /**
     * @throws InvalidRequestTypeException
     */
    public function build(PSRRequestInterface $request): CurlRequestOptionsBuilderInterface
    {
        switch (strtoupper($request->getMethod())) {
            case HttpRequestType::GET:
                return new GetCurlRequestOptionsBuilder();
            case HttpRequestType::POST:
            case HttpRequestType::PUT:
            case HttpRequestType::DELETE:
            case HttpRequestType::PATCH:
                return new PostCurlRequestOptionsBuilder();
            default:
                throw new InvalidRequestTypeException('Invalid method type.');
        }
    }
}
