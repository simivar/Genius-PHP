<?php
declare(strict_types=1);

namespace Genius;

use Genius\Authentication\OAuth2;
use Genius\HttpClient\ClientConfiguration;
use Genius\HttpClient\Requester;
use Genius\Resources;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Message\Authentication;

class Genius
{
    /** @var Authentication|OAuth2 */
    protected $authentication;

    /** @var ClientConfiguration */
    protected $clientConfiguration;

    /** @var Requester */
    protected $requester;

    /** @var HttpClient */
    protected $httpClient;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function setClientConfiguration(ClientConfiguration $clientConfiguration): void
    {
        $this->clientConfiguration = $clientConfiguration;
    }

    public function setRequester(Requester $requester): void
    {
        $this->requester = $requester;
    }

    private function getRequester(): Requester
    {
        if ($this->requester === null) {
            $this->requester = new Requester($this->getClient());
        }

        return $this->requester;
    }

    protected function getClient(): PluginClient
    {
        if ($this->clientConfiguration === null) {
            $this->clientConfiguration = new ClientConfiguration($this->authentication);
        }

        if ($this->httpClient === null) {
            $this->httpClient = $this->clientConfiguration->createClient();
        }

        return $this->httpClient;
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
