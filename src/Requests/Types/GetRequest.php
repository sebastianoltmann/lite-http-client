<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests\Types;

use LiteHttpClient\Requests\AbstractRequest;
use LiteHttpClient\Requests\HttpRequestType;

class GetRequest extends AbstractRequest
{
    public function method(): string
    {
        return HttpRequestType::GET;
    }
}
