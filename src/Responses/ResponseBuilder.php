<?php

declare(strict_types=1);

namespace LiteHttpClient\Responses;

use InvalidArgumentException;
use Psr\Http\Message\ResponseFactoryInterface as PsrResponseFactory;
use Psr\Http\Message\ResponseInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    protected const STATUS_SEPARATOR = ' ';
    protected const HEADER_SEPARATOR = ':';

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var PSRResponseFactory
     */
    private $responseFactory;

    public function __construct(PSRResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->response = $responseFactory->createResponse();
    }

    public function setStatus(string $input): void
    {
        $parts = explode(self::STATUS_SEPARATOR, $input, 3);
        if (\count($parts) < 2 || 0 !== strpos(strtolower($parts[0]), 'http/')) {
            throw new InvalidArgumentException(sprintf('"%s" is not a valid HTTP status line', $input));
        }

        $this->response = $this->response->withStatus((int)$parts[1], isset($parts[2]) ? $parts[2] : '');
        $this->response = $this->response->withProtocolVersion((string)substr($parts[0], 5));
    }

    public function addHeader(string $input): void
    {
        [$key, $value] = explode(self::HEADER_SEPARATOR, $input, 2);
        $this->response = $this->response->withAddedHeader(trim($key), trim($value));
    }

    public function writeBody(string $input): int
    {
        return $this->response->getBody()->write($input);
    }

    public function getResponse(): ResponseInterface
    {
        $this->response->getBody()->rewind();

        $response = $this->response;
        $this->response = $this->responseFactory->createResponse();

        return $response;
    }
}
