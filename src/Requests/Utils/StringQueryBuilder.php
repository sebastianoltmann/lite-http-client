<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests\Utils;

use Psr\Http\Message\UriInterface;

class StringQueryBuilder
{
    private const QUERY_SEPARATOR = '&';

    /**
     * @var UriInterface|null
     */
    private $uri;

    public function __construct(UriInterface $uri = null)
    {
        $this->uri = $uri;
    }

    public function build(array $query = []): string
    {
        $query = http_build_query($query);

        if ($this->uri instanceof UriInterface) {
            $queryUri = $this->uri->getQuery();

            $query = join(self::QUERY_SEPARATOR, [$queryUri, $query]);
        }

        return trim($query, self::QUERY_SEPARATOR);
    }
}
