<?php

declare(strict_types=1);

namespace Genius\HttpClient;

use Psr\Http\Client\ClientInterface;
use stdClass;

interface RequesterInterface
{
    public const OK_STATUS_CODE = 200;

    public function __construct(ClientInterface $httpClient, RequestBuilderInterface $requestBuilder);

    /**
     * @param array<string, string|int|null> $parameters
     * @param array<string, string> $headers
     */
    public function get(string $uri, array $parameters = [], array $headers = []): stdClass;

    /**
     * @param array<string, string|int|array|null> $parameters
     * @param array<string, string> $headers
     */
    public function post(string $uri, array $parameters = [], array $headers = []): stdClass;

    /**
     * @param array<string, string|int|array|null> $parameters
     * @param array<string, string> $headers
     */
    public function put(string $uri, array $parameters = [], array $headers = []): stdClass;

    /**
     * @param array<string, string|int|null> $parameters
     * @param array<string, string> $headers
     */
    public function delete(string $uri, array $parameters = [], array $headers = []): stdClass;
}
