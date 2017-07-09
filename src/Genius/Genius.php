<?php

namespace Genius;

use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;

class Genius
{
    
    /** @var MessageFactory */
    protected $requestFactory;
    
    /** @var PluginClient */
    protected $httpClient;
    
    /** @var Authentication */
    protected $authentication;
    
    /**
     * ClientGenius constructor.
     *
     * @param Authentication $authentication
     * @param HttpClient|null $httpClient
     */
    public function __construct(Authentication $authentication, HttpClient $httpClient = null)
    {
        $this->authentication = $authentication;
        $this->requestFactory = MessageFactoryDiscovery::find();
        
        $connection = new ConnectGenius($authentication);
        
        if ($httpClient !== null) {
            $connection->setHttpClient($httpClient);
        }
        
        $this->httpClient = $connection->createConnection();
    }
    
    /**
     * @return PluginClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
    
    /**
     * @return Authentication
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }
    
    /**
     * @return MessageFactory
     */
    public function getRequestFactory()
    {
        return $this->requestFactory;
    }
    
}
