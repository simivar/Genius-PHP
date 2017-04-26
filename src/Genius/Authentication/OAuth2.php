<?php

namespace Genius\Authentication;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;

/**
 * Class OAuth2. OAuth2 implementation using HTTP.io.
 * @package Genius\Authentication
 */
final class OAuth2 implements Authentication
{
    
    const API_URL = 'https://api.genius.com/oauth/';
    
    /**
     * @var string
     */
    private $clientSecret;
    
    /**
     * @var string
     */
    private $clientId;
    
    /**
     * @var string
     */
    protected $state;
    
    /**
     * @var string
     */
    private $accessToken;
    
    /**
     * @var Scope
     */
    private $scope;
    
    /**
     * @var HttpClient
     */
    private $httpClient;
    
    /**
     * @var MessageFactory
     */
    private $messageFactory;
    
    /**
     * @var string
     */
    private $redirectUri;
    
    /**
     * @param string $client_id
     * @param string $client_secret
     * @param string $redirect_uri
     * @param Scope $scope
     * @param HttpClient $httpClient
     */
    public function __construct($client_id, $client_secret, $redirect_uri, Scope $scope, HttpClient $httpClient = null)
    {
        $this->clientId = $client_id;
        $this->clientSecret = $client_secret;
        $this->redirectUri = $redirect_uri;
        
        if ($httpClient === null) {
            $this->getHttpClient();
        } else {
            $this->httpClient = $httpClient;
        }
        $this->messageFactory = MessageFactoryDiscovery::find();
        $this->setScope($scope);
        $this->state = $this->getRandomState();
    }
    
    public function setScope(Scope $scope)
    {
        $this->scope = $scope;
        
        return $this;
    }
    
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        
        return $this;
    }
    
    public function setAccessToken($acces_token)
    {
        $this->accessToken = $acces_token;
        
        return $this;
    }
    
    public function setRedirectUri($redirect_uri)
    {
        $this->redirectUri = $redirect_uri;
        
        return $this;
    }
    
    public function setMessageFactory(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
        
        return $this;
    }
    
    public function setState($state)
    {
        $this->state = $state;
        
        return $this;
    }
    
    public function getAccessToken()
    {
        if ($this->hasValidAccessToken()) {
            return $this->accessToken;
        }
        
        $this->accessToken = null;
        
        return false;
    }
    
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        }
        
        return $this->httpClient;
    }
    
    public function getAuthUrl()
    {
        return self::API_URL . 'authorize?client_id=' . $this->clientId .
            '&redirect_uri=' . $this->redirectUri . '&scope=' . $this->scope . '&state=' . 'randstate' .
            '&response_type=code';
    }
    
    public function getState()
    {
        return $this->state;
    }
    
    public function hasValidAccessToken()
    {
        if ($this->accessToken !== null) {
            return true;
        }
        
        return false;
    }
    
    public function refreshToken($code)
    {
        if ($this->getAccessToken()) {
            return $this->getAccessToken();
        }
        
        $request = $this->httpClient->sendRequest(
            $this->messageFactory->createRequest(
                'POST', self::API_URL . 'token', [], http_build_query([
                    'code' => $code,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->clientId,
                    'redirect_uri' => $this->redirectUri,
                    'response_type' => 'code',
                ])
            )
        );
        
        if ($request->getStatusCode() === 200) {
            $body = json_decode($request->getBody());
            
            $this->accessToken = $body->access_token;
            
            return $this->accessToken;
        }
        
        return false;
    }
    
    protected function getRandomState($length = 32)
    {
        // Converting bytes to hex will always double length. Hence, we can reduce
        // the amount of bytes by half to produce the correct length.
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request)
    {
        if ($this->hasValidAccessToken()) {
            $header = sprintf('Bearer %s', $this->accessToken);
            
            return $request->withHeader('Authorization', $header);
        }
        
        return $request;
    }
}
