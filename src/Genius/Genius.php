<?php
declare(strict_types=1);

namespace Genius;

use Genius\Authentication\OAuth2;
use Genius\Exception\ConnectGeniusException;
use Genius\HttpClient\Requester;
use Genius\Resources;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Genius
{
    /** @var RequestFactoryInterface */
    protected $requestFactory;
    
    /** @var PluginClient */
    protected $httpClient;
    
    /** @var Authentication|OAuth2 */
    protected $authentication;
    
    /**
     * ClientGenius constructor.
     *
     * @param Authentication $authentication
     * @param HttpClient|null $httpClient
     * @throws ConnectGeniusException
     */
    public function __construct(Authentication $authentication, ?HttpClient $httpClient = null)
    {
        $this->authentication = $authentication;
        $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        
        $connection = new ConnectGenius($authentication);
        
        if ($httpClient !== null) {
            $connection->setHttpClient($httpClient);
        }
        
        $this->httpClient = $connection->createConnection();
    }

    public function getHttpClient(): PluginClient
    {
        return $this->httpClient;
    }

    /**
     * @return Authentication|OAuth2
     */
    public function getAuthentication(): Authentication
    {
        return $this->authentication;
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return Psr17FactoryDiscovery::findStreamFactory();
    }

    public function getAccountResource(): Resources\AccountResource
    {
        return new Resources\AccountResource(new Requester($this->getHttpClient()));
    }

    public function getAnnotationsResource(): Resources\AnnotationsResource
    {
        return new Resources\AnnotationsResource(new Requester($this->getHttpClient()));
    }

    public function getArtistsResource(): Resources\ArtistsResource
    {
        return new Resources\ArtistsResource(new Requester($this->getHttpClient()));
    }

    public function getReferentsResource(): Resources\ReferentsResource
    {
        return new Resources\ReferentsResource(new Requester($this->getHttpClient()));
    }

    public function getSearchResource(): Resources\SearchResource
    {
        return new Resources\SearchResource(new Requester($this->getHttpClient()));
    }

    public function getSongsResource(): Resources\SongsResource
    {
        return new Resources\SongsResource(new Requester($this->getHttpClient()));
    }

    public function getWebPagesResource(): Resources\WebPagesResource
    {
        return new Resources\WebPagesResource(new Requester($this->getHttpClient()));
    }
}
