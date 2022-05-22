<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients;

use Psr\Http\Client\ClientInterface as PSRClientInterface;

interface ClientInterface extends PSRClientInterface
{
    public function setOption($option, $value): bool;
}
