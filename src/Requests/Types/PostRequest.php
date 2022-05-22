<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests\Types;

use LiteHttpClient\Requests\AbstractRequest;
use LiteHttpClient\Requests\HttpRequestType;

class PostRequest extends AbstractRequest
{
    public function method(): string
    {
        return HttpRequestType::POST;
    }
}
