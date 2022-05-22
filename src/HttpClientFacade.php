<?php

declare(strict_types=1);

namespace LiteHttpClient;

use Psr\Http\Message\ResponseInterface;

/**
 * @method static ResponseInterface get(string $uri, array $query = [], array $headers = [])
 * @method static ResponseInterface post(string $uri, array $body = [], array $headers = [], array $query = [])
 * @method static ResponseInterface patch(string $uri, array $body = [], array $headers = [], array $query = [])
 * @method static ResponseInterface put(string $uri, array $body = [], array $headers = [], array $query = [])
 * @method static ResponseInterface delete(string $uri, array $headers = [])
 */
class HttpClientFacade
{
    public static function client(): HttpClientInterface
    {
        return new HttpClient();
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array([self::client(), $method], $args);
    }
}
