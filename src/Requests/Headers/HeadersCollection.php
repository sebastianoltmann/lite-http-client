<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests\Headers;

use Countable;

class HeadersCollection implements Countable
{
    /**
     * @var array
     */
    private $headers;

    public function __construct(array $headers = [])
    {
        $this->headers = $headers;
    }

    /**
     * @param mixed $value
     */
    public function add(string $key, $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function addMany(array $headers = []): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->headers[$key] : $default;
    }

    public function keys(): array
    {
        return array_keys($this->headers);
    }

    public function all(): array
    {
        return $this->headers;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->headers);
    }

    public function each(callable $callback): self
    {
        foreach ($this->headers as $key => $item) {
            if ($callback($key, $item) === false) {
                break;
            }
        }

        return $this;
    }

    public function count(): int
    {
        return count($this->headers);
    }
}
