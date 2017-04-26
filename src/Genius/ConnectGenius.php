<?php

namespace Genius;

use Genius\Authentication\OAuth2;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\UriFactory;
use Psr\Http\Message\UriInterface;

class ConnectGenius
{
    
    protected $endpoint = 'http://api.genius.com/';
    
    /** @var HttpClient */
    protected $httpClient;
    
    /** @var  UriFactory */
    protected $uriFactory;
    
    /** @var Authentication */
    protected $authentication;
    
    public function __construct(Authentication $authentication)
    {
        $this->setAuthentication($authentication);
    }
    
    public function createConnection()
    {
        $plugins = [
            new AddHostPlugin($this->getUriFactory()->createUri($this->endpoint)),
            new AuthenticationPlugin($this->authentication),
        ];
        
        $client = new PluginClient(
            $this->getHttpClient(),
            $plugins
        );
        
        return $client;
    }
    
    public function setUriFactory(UriInterface $uriFactory)
    {
        $this->uriFactory = $uriFactory;
        
        return $this;
    }
    
    public function getUriFactory()
    {
        if ($this->uriFactory === null) {
            
            $this->uriFactory = UriFactoryDiscovery::find();
        }
        
        return $this->uriFactory;
    }
    
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        
        return $this;
    }
    
    public function setAuthentication(Authentication $authentication)
    {
        if ($authentication instanceof Authentication\Bearer || $authentication instanceof OAuth2) {
            $this->authentication = $authentication;
            
            return true;
        }
        
        throw new ConnectGeniusException('Genius API supports only Bearer and OAuth2 authentication.');
    }
    
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        }
        
        return $this->httpClient;
    }
    
}