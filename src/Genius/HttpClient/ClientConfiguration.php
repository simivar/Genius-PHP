<?php

namespace Genius\HttpClient;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Authentication;
use Http\Client\HttpClient;
use Psr\Http\Message\UriFactoryInterface;

class ClientConfiguration {
    protected $endpoint = 'https://api.genius.com/';

    /** @var Authentication */
    protected $authentication;

    /** @var HttpClient */
    protected $httpClient;

    /** @var UriFactoryInterface */
    protected $uriFactory;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function createClient(): PluginClient
    {
        return (new PluginClientFactory())->createClient($this->getHttpClient(), $this->getPlugins());
    }

    public function setHttpClient(HttpClient $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    private function getHttpClient(): HttpClient
    {
        if ($this->httpClient === null) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        return $this->httpClient;
    }

    public function setUriFactory(UriFactoryInterface $uriFactory): void
    {
        $this->uriFactory = $uriFactory;
    }

    private function getUriFactory(): UriFactoryInterface
    {
        if ($this->uriFactory === null) {
            $this->uriFactory = Psr17FactoryDiscovery::findUrlFactory();
        }

        return $this->uriFactory;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    private function getPlugins(): array
    {
        return [
            new AddHostPlugin($this->getUriFactory()->createUri($this->endpoint)),
            new HeaderDefaultsPlugin([
                'User-Agent' => 'simivar/genius-php; version 3',
            ]),
            new AuthenticationPlugin($this->authentication),
        ];
    }
}
