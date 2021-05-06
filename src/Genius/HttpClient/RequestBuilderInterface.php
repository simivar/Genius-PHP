<?php

declare(strict_types=1);

namespace Genius\HttpClient;

use Psr\Http\Message\RequestInterface;

interface RequestBuilderInterface
{
    /**
     * @param array<string, string> $headers
     */
    public function build(string $method, string $uri, array $headers = [], ?string $body = null): RequestInterface;
}
