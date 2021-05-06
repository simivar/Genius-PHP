<?php

declare(strict_types=1);

namespace Genius\HttpClient;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class RequestBuilder implements RequestBuilderInterface
{
    private RequestFactoryInterface $requestFactory;
    private StreamFactoryInterface $streamFactory;

    public function build(string $method, string $uri, array $headers = [], ?string $body = null): RequestInterface
    {
        $request = $this->getRequestFactory()->createRequest($method, $uri);
        if (null !== $body) {
            $request->withBody($this->getStreamFactory()->createStream($body));
        }

        foreach ($headers as $name => $value) {
            $request->withAddedHeader($name, $value);
        }

        return $request;
    }

    public function setRequestFactory(RequestFactoryInterface $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
    }

    private function getRequestFactory(): RequestFactoryInterface
    {
        if (!isset($this->requestFactory)) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory): void
    {
        $this->streamFactory = $streamFactory;
    }

    private function getStreamFactory(): StreamFactoryInterface
    {
        if (!isset($this->streamFactory)) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        return $this->streamFactory;
    }
}
