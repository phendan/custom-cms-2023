<?php

namespace App;

use App\Helpers\Str;

class Request {
    private array $pageParams;
    private array $headers;

    public function __construct(array $pageParams)
    {
        $this->pageParams = $pageParams;
        $this->headers = $this->parseHeaders();
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getInput(string $kind = 'post'): array
    {
        $input = match($kind) {
            'post' => $this->sanitizeInput($_POST),
            'get' => $this->sanitizeInput($_GET),
            'file' => $_FILES,
            'page' => $this->pageParams,
            'json' => json_decode(file_get_contents('php://input'), true)
        };

        return $input;
    }

    private function sanitizeInput(array $input): array
    {
        return array_map(function ($element) {
            return htmlspecialchars(trim($element));
        }, $input);
    }

    private function parseHeaders(): array
    {
        $rawHeaders = array_filter($_SERVER, function ($key) {
            return str_starts_with($key, 'HTTP_');
        }, ARRAY_FILTER_USE_KEY);

        $headers = [];

        foreach ($rawHeaders as $key => $header) {
            $headers[Str::toHeaderCase($key)] = $header;
        }

        return $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function expectsJson(): bool
    {
        return in_array('Accept', array_keys($this->headers)) && str_contains($this->headers['Accept'], 'application/json');
    }
}
