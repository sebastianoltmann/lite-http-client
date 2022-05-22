<?php

declare(strict_types=1);

namespace LiteHttpClient;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function get(string $uri, array $query = [], array $headers = []): ResponseInterface;
    
    public function post(string $uri, array $body = [], array $headers = [], array $query = []): ResponseInterface;

    public function patch(string $uri, array $body = [], array $headers = [], array $query = []): ResponseInterface;

    public function put(string $uri, array $body = [], array $headers = [], array $query = []): ResponseInterface;

    public function delete(string $uri, array $headers = []): ResponseInterface;
}
