<?php

namespace Genius\HttpClient;

use Genius\Exception\ApiResponseErrorException;
use Genius\Genius;
use Psr\Http\Message\RequestInterface;
use stdClass;

class Requester {
    protected const API_URL = 'https://api.genius.com/';

    protected const OK_STATUS_CODE = 200;

    /** @var Genius */
    private $genius;

    public function __construct(Genius $genius)
    {
        $this->genius = $genius;
    }

    public function get(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        if (count($parameters) > 0) {
            $uri .= '/?'.http_build_query($parameters);
        }

        return $this->sendRequest(
            'GET',
            $uri,
            $headers,
        );
    }

    public function post(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        return $this->sendRequest(
            'POST',
            $uri,
            $headers,
            json_encode($parameters, JSON_THROW_ON_ERROR),
        );
    }

    public function put(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        return $this->sendRequest(
            'PUT',
            $uri,
            $headers,
            json_encode($parameters, JSON_THROW_ON_ERROR),
        );
    }

    public function delete(string $uri, array $parameters = [], array $headers = []): stdClass
    {
        return $this->sendRequest(
            'DELETE',
            $uri,
            $headers,
            json_encode($parameters, JSON_THROW_ON_ERROR),
        );
    }

    protected function sendRequest(
        string $method,
        string $uri,
        array $headers = [],
        $body = null
    ): stdClass
    {
        $req = $this->createRequest($method, $uri, $headers, $body);

        $response = $this->genius->getHttpClient()->sendRequest($req);
        $decodedBody = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        if ($response->getStatusCode() === self::OK_STATUS_CODE) {
            return $decodedBody->response;
        }

        if (isset($decodedBody->meta)) {
            throw new ApiResponseErrorException($decodedBody->meta->message, $response->getStatusCode());
        }

        throw new ApiResponseErrorException($decodedBody->error_description, $response->getStatusCode());
    }

    private function createRequest(string $method, string $uri, array $headers = [], ?string $body = null, string $protocolVersion = '1.1'): RequestInterface
    {
        $request = $this->genius->getRequestFactory()->createRequest($method, self::API_URL . $uri);
        $request->withProtocolVersion($protocolVersion);

        if (is_string($body)) {
            $request->withBody($this->genius->getStreamFactory()->createStream($body));
        }

        foreach ($headers as $name => $value) {
            $request->withAddedHeader($name, $value);
        }

        return $request;
    }
}
