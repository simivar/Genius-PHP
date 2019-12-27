<?php
declare(strict_types=1);

namespace Genius;

use Genius\Authentication\OAuth2;
use Genius\Resources;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;

/**
 * Class Genius
 * @package Genius
 *
 * @method Resources\AccountResource getAccountResource()
 * @method Resources\AnnotationsResource getAnnotationsResource()
 * @method Resources\ArtistsResource getArtistsResource()
 * @method Resources\ReferentsResource getReferentsResource()
 * @method Resources\SearchResource getSearchResource()
 * @method Resources\SongsResource getSongsResource()
 * @method Resources\WebPagesResource getWebPagesResource()
 */
class Genius
{
    /** @var MessageFactory */
    protected $requestFactory;
    
    /** @var PluginClient */
    protected $httpClient;
    
    /** @var Authentication|OAuth2 */
    protected $authentication;
    
    /** @var array All created resource objects */
    protected $resourceObjects = [];
    
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
        $this->requestFactory = MessageFactoryDiscovery::find();
        
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

    public function getAuthentication(): Authentication
    {
        return $this->authentication;
    }

    public function getRequestFactory(): MessageFactory
    {
        return $this->requestFactory;
    }
    
    public function __call($name, $arguments)
    {
        if (strpos($name, 'get') !== 0) {
            return false;
        }
        
        $name = '\\Genius\\Resources\\' . substr($name, 3);
        if (!class_exists($name)) {
            return false;
        }
        
        if (isset($this->resourceObjects[ $name ])) {
            return $this->resourceObjects[ $name ];
        }
        
        $this->resourceObjects[ $name ] = new $name($this);
        
        return $this->resourceObjects[ $name ];
    }
}
