<?php
declare(strict_types=1);

namespace Genius;

use Genius\Authentication\OAuth2;
use Genius\Exception\ConnectGeniusException;
use Genius\Resources;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

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
    /** @var RequestFactoryInterface */
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
