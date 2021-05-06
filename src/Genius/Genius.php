<?php

declare(strict_types=1);

namespace Genius;

use Genius\HttpClient\ClientConfiguration;
use Genius\HttpClient\ClientConfigurationInterface;
use Genius\HttpClient\RequestBuilder;
use Genius\HttpClient\Requester;
use Genius\HttpClient\RequesterInterface;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Message\Authentication;

class Genius
{
    protected Authentication $authentication;
    protected ClientConfigurationInterface $clientConfiguration;
    protected RequesterInterface $requester;
    protected HttpClient $httpClient;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function setClientConfiguration(ClientConfigurationInterface $clientConfiguration): void
    {
        $this->clientConfiguration = $clientConfiguration;
    }

    public function setRequester(RequesterInterface $requester): void
    {
        $this->requester = $requester;
    }

    private function getRequester(): RequesterInterface
    {
        if (!isset($this->requester)) {
            $this->requester = new Requester($this->getClient(), new RequestBuilder());
        }

        return $this->requester;
    }

    private function getClient(): PluginClient
    {
        if (!isset($this->httpClient)) {
            $this->httpClient = $this->getClientConfiguration()->createClient();
        }

        return $this->httpClient;
    }

    private function getClientConfiguration(): ClientConfigurationInterface
    {
        if (!isset($this->clientConfiguration)) {
            $this->clientConfiguration = new ClientConfiguration($this->authentication);
        }

        return $this->clientConfiguration;
    }

    public function getAccountResource(): Resources\AccountResource
    {
        return new Resources\AccountResource($this->getRequester());
    }

    public function getAnnotationsResource(): Resources\AnnotationsResource
    {
        return new Resources\AnnotationsResource($this->getRequester());
    }

    public function getArtistsResource(): Resources\ArtistsResource
    {
        return new Resources\ArtistsResource($this->getRequester());
    }

    public function getReferentsResource(): Resources\ReferentsResource
    {
        return new Resources\ReferentsResource($this->getRequester());
    }

    public function getSearchResource(): Resources\SearchResource
    {
        return new Resources\SearchResource($this->getRequester());
    }

    public function getSongsResource(): Resources\SongsResource
    {
        return new Resources\SongsResource($this->getRequester());
    }

    public function getWebPagesResource(): Resources\WebPagesResource
    {
        return new Resources\WebPagesResource($this->getRequester());
    }
}
