<?php

namespace Genius;

use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;

class Genius
{
    
    /** @var RequestInterface */
    protected $requestFactory;
    
    /** @var ConnectGenius */
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
    
}