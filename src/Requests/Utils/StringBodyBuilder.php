<?php

declare(strict_types=1);

namespace LiteHttpClient\Requests\Utils;

class StringBodyBuilder
{
    /**
     * @var string
     */
    private $boundary;

    public function __construct()
    {
        $this->boundary = uniqid('', true);
    }

    public function boundary(): string
    {
        return $this->boundary;
    }

    public function build(array $body): string
    {
        $files = $this->files($body);

        if (empty($files)) {
            return http_build_query($this->fields($body));
        }

        foreach ($this->fields($body) as $name => $value) {
            $files .= $this->prepareMultipart((string)$name, $value);
        }

        return "$files--{$this->boundary()}--\r\n";
    }

    private function fields(array $body): array
    {
        return array_filter($body, function ($value) {
            return !isset($value['path']);
        });
    }

    private function files(array $body): string
    {
        return join('', array_map(
            function ($name, $value) {
                $fileContent = file_get_contents($value['path']);

                return $fileContent !== false
                    ? $this->prepareMultipart($name, $fileContent, $value)
                    : '';
            },
            array_filter($body, function ($value) {
                return isset($value['path']);
            })
        ));
    }

    private function prepareMultipart(string $name, string $content, array $data = []): string
    {
        $fileHeaders = [];

        $fileHeaders['Content-Disposition'] = sprintf('form-data; name="%s"', $name);
        if (isset($data['filename'])) {
            $fileHeaders['Content-Disposition'] .= sprintf('; filename="%s"', $data['filename']);
        }

        if ($length = strlen($content)) {
            $fileHeaders['Content-Length'] = (string)$length;
        }

        if (isset($data['contentType'])) {
            $fileHeaders['Content-Type'] = $data['contentType'];
        }

        $output = "--{$this->boundary()}\r\n";
        foreach ($fileHeaders as $key => $value) {
            $output .= sprintf("%s: %s\r\n", $key, $value);
        }
        $output .= "\r\n";
        $output .= $content;
        $output .= "\r\n";

        return $output;
    }
}
