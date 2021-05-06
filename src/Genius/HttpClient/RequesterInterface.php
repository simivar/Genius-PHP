<?php

namespace Genius\HttpClient;

use Psr\Http\Client\ClientInterface;
use stdClass;

interface RequesterInterface
{
    public function __construct(ClientInterface $httpClient, RequestBuilderInterface $requestBuilder);

    public function get(string $uri, array $parameters = [], array $headers = []): stdClass;

    public function post(string $uri, array $parameters = [], array $headers = []): stdClass;

    public function put(string $uri, array $parameters = [], array $headers = []): stdClass;

    public function delete(string $uri, array $parameters = [], array $headers = []): stdClass;
}
