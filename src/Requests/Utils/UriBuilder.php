<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests\Utils;

use LiteHttpClient\Exceptions\InvalidUriException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class UriBuilder implements UriBuilderInterface
{
    public const URI_SLASH = '/';

    /**
     * @var UriFactoryInterface
     */
    private $factory;

    public function __construct()
    {
        $this->factory = new Psr17Factory();
    }

    /**
     * @throws InvalidUriException
     */
    public function prepare(string $uri, ?UriInterface $baseUri = null): UriInterface
    {
        $uri = trim($uri, self::URI_SLASH);

        $uri = $baseUri instanceof UriInterface
            ? $baseUri->withPath(
                $this->preparePath($baseUri, $uri)
            )
            : $this->factory->createUri($uri);

        if (!$uri->getHost()) {
            throw new InvalidUriException('Host is required in url address.');
        }

        return $uri;
    }

    /**
     * @throws InvalidUriException
     */
    private function preparePath(UriInterface $baseUri, string $uri): string
    {
        $pathUri = $this->factory->createUri($uri);

        if ($pathUri->getHost() || $pathUri->getScheme()) {
            throw new InvalidUriException('The path cannot contain a domain.');
        }

        return join(self::URI_SLASH, [$baseUri->getPath(), $pathUri->getPath()]);
    }
}
