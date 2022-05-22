<?php

declare(strict_types=1);

namespace LiteHttpClient\Clients\Curl;

class CurlHeadersConverter
{
    private const HEADER_PATTERN = '%s: %s';

    public function convert(array $headers = []): array
    {
        $output = [];

        foreach ($headers as $key => $values) {
            if (!is_array($values)) {
                $output[] = $this->parseToString($key, $values);
            } else {
                foreach ($values as $value) {
                    $output[] = $this->parseToString($key, $value);
                }
            }
        }

        return $output;
    }

    private function parseToString(string $key, string $value): string
    {
        return sprintf(self::HEADER_PATTERN, $key, $value);
    }
}
