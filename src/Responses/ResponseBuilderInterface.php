<?php

declare(strict_types=1);

namespace LiteHttpClient\Responses;

use Psr\Http\Message\ResponseFactoryInterface as PSRResponseFactory;
use Psr\Http\Message\ResponseInterface;

interface ResponseBuilderInterface
{
    public function __construct(PSRResponseFactory $responseFactory);

    public function getResponse(): ResponseInterface;
}
