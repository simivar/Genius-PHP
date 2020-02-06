<?php
declare(strict_types=1);

namespace Genius\HttpClient;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

class RequestBuilder
{
    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function build(string $method, string $uri, array $headers = [], $body = null): RequestInterface
    {
        $request = $this->getRequestFactory()->createRequest($method, $uri);
        if (is_string($body)) {
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
        if ($this->requestFactory === null) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    public function setStreamFactory(StreamFactoryInterface $streamFactory): void
    {
        $this->streamFactory = $streamFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        if ($this->streamFactory === null) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        return $this->streamFactory;
    }
}
