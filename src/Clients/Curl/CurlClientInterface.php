<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

use Psr\Http\Client\ClientInterface;

interface CurlClientInterface extends ClientInterface
{
    public function setOption($option, $value): bool;
}
