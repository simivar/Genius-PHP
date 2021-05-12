<?php

declare(strict_types=1);

namespace Genius\HttpClient;

use Genius\Exception\ApiResponseErrorException;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use stdClass;

final class Requester implements RequesterInterface
{
    private ClientInterface $httpClient;
    private RequestBuilderInterface $requestBuilder;

    public function __construct(ClientInterface $httpClient, RequestBuilderInterface $requestBuilder)
    {
        $this->httpClient = $httpClient;
        $this->requestBuilder = $requestBuilder;
    }

    public function get(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        if (count($parameters) > 0) {
            $uri .= '/?' . http_build_query($parameters);
        }

        return $this->sendRequest(
            'GET',
            $uri,
            $headers
        );
    }

    /**
     * @throws ApiResponseErrorException
     * @throws JsonException
     */
    public function post(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        return $this->sendRequest(
            'POST',
            $uri,
            $headers,
            http_build_query($parameters)
        );
    }

    public function put(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        return $this->sendRequest(
            'PUT',
            $uri,
            $headers,
            json_encode($parameters, JSON_THROW_ON_ERROR)
        );
    }

    public function delete(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        return $this->sendRequest(
            'DELETE',
            $uri,
            $headers,
            json_encode($parameters, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws ApiResponseErrorException
     * @throws JsonException
     */
    private function sendRequest(
        string $method,
        string $uri,
        array $headers = [],
        ?string $body = null
    ): stdClass {
        $request = $this->requestBuilder->build($method, $uri, $headers, $body);

        try {
            $response = $this->httpClient->sendRequest($request);
            $decodedBody = json_decode(
                $response->getBody()->getContents(),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (ClientExceptionInterface $e) {
            throw new ApiResponseErrorException($e->getMessage(), $e->getCode());
        } catch (JsonException $jsonException) {
            throw $jsonException;
        }

        if (self::OK_STATUS_CODE === $response->getStatusCode()) {
            return $decodedBody->response ?? $decodedBody;
        }

        if (isset($decodedBody->meta)) {
            throw new ApiResponseErrorException($decodedBody->meta->message, $response->getStatusCode());
        }

        throw new ApiResponseErrorException($decodedBody->error_description, $response->getStatusCode());
    }
}
