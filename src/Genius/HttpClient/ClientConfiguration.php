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
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriFactoryInterface;

final class ClientConfiguration {
    private string $endpoint = 'https://api.genius.com/';
    private Authentication $authentication;
    private ClientInterface $httpClient;
    private UriFactoryInterface $uriFactory;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function createClient(): PluginClient
    {
        return (new PluginClientFactory())->createClient($this->getHttpClient(), $this->getPlugins());
    }

    public function setHttpClient(ClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    private function getHttpClient(): ClientInterface
    {
        if (!isset($this->httpClient)) {
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
        if (!isset($this->uriFactory)) {
            $this->uriFactory = Psr17FactoryDiscovery::findUriFactory();
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
