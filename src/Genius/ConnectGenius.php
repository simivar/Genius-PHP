<?php
declare(strict_types=1);

namespace Genius;

use Genius\Authentication\OAuth2;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\Common\PluginClientFactory;
use Http\Client\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\Authentication;
use Psr\Http\Message\UriFactoryInterface;
use Genius\Exception\ConnectGeniusException;

class ConnectGenius
{
    protected const ENDPOINT = 'http://api.genius.com/';
    
    /** @var HttpClient */
    protected $httpClient;
    
    /** @var  UriFactoryInterface */
    protected $uriFactory;
    
    /** @var Authentication|OAuth2 */
    protected $authentication;

    /**
     * ConnectGenius constructor.
     * @param Authentication|OAuth2 $authentication
     * @throws ConnectGeniusException
     */
    public function __construct(Authentication $authentication)
    {
        $this->setAuthentication($authentication);
    }
    
    public function createConnection(): PluginClient
    {
        $plugins = [
            new AddHostPlugin($this->getUriFactory()->createUri(self::ENDPOINT)),
            new AuthenticationPlugin($this->authentication),
        ];

        return (new PluginClientFactory())->createClient($this->getHttpClient(), $plugins);
    }
    
    public function setUriFactory(UriFactoryInterface $uriFactory): ConnectGenius
    {
        $this->uriFactory = $uriFactory;
        
        return $this;
    }
    
    public function getUriFactory(): UriFactoryInterface
    {
        if ($this->uriFactory === null) {
            $this->uriFactory = Psr17FactoryDiscovery::findUrlFactory();
        }
        
        return $this->uriFactory;
    }
    
    public function setHttpClient(HttpClient $httpClient): ConnectGenius
    {
        $this->httpClient = $httpClient;
        
        return $this;
    }

    /**
     * @param Authentication $authentication
     * @return bool
     * @throws ConnectGeniusException
     */
    public function setAuthentication(Authentication $authentication): bool
    {
        if ($authentication instanceof Authentication\Bearer || $authentication instanceof OAuth2) {
            $this->authentication = $authentication;
            
            return true;
        }
        
        throw new ConnectGeniusException('Genius API supports only Bearer and OAuth2 authentication.');
    }
    
    protected function getHttpClient(): HttpClient
    {
        if ($this->httpClient === null) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }
        
        return $this->httpClient;
    }
    
}
