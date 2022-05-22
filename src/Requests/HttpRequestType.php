<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests;

final class HttpRequestType
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';
    public const OPTIONS = 'OPTIONS';
}
