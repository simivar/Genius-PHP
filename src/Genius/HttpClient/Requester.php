<?php

namespace Genius\HttpClient;

use Genius\Exception\ApiResponseErrorException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use stdClass;

class Requester
{
    private const OK_STATUS_CODE = 200;

    /** @var ClientInterface */
    private $httpClient;

    /** @var RequestBuilder */
    private $requestBuilder;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function setRequestBuilder(RequestBuilder $requestBuilder): void
    {
        $this->requestBuilder = $requestBuilder;
    }

    private function getRequestBuilder(): RequestBuilder
    {
        if ($this->requestBuilder === null) {
            $this->requestBuilder = new RequestBuilder();
        }

        return $this->requestBuilder;
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

    public function post(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        return $this->sendRequest(
            'POST',
            $uri,
            $headers,
            json_encode($parameters, JSON_THROW_ON_ERROR)
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

    protected function sendRequest(
        string $method,
        string $uri,
        array $headers = [],
        ?string $body = null
    ): stdClass
    {
        $request = $this->getRequestBuilder()->build($method, $uri, $headers, $body);

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
        } catch (\JsonException $jsonException) {
            throw $jsonException;
        }

        if ($response->getStatusCode() === self::OK_STATUS_CODE) {
            return $decodedBody->response;
        }

        if (isset($decodedBody->meta)) {
            throw new ApiResponseErrorException($decodedBody->meta->message, $response->getStatusCode());
        }

        throw new ApiResponseErrorException($decodedBody->error_description, $response->getStatusCode());
    }
}
