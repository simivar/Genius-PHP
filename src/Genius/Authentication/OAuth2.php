<?php

declare(strict_types=1);

namespace Genius\Authentication;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;

final class OAuth2 implements Authentication
{
    private const API_URL = 'https://api.genius.com/oauth/';

    private string $clientSecret;
    private string $clientId;
    private string $state;
    private ?string $accessToken;
    private ScopeList $scopeList;
    private ?HttpClient $httpClient;
    private MessageFactory $messageFactory;
    private string $redirectUri;

    public function __construct(string $clientId, string $clientSecret, string $redirectUri, ScopeList $scope, ?HttpClient $httpClient = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->httpClient = $httpClient;
        $this->messageFactory = MessageFactoryDiscovery::find();
        $this->scopeList = $scope;
    }

    public function setAccessToken(string $accessToken): OAuth2
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function setMessageFactory(MessageFactory $messageFactory): OAuth2
    {
        $this->messageFactory = $messageFactory;

        return $this;
    }

    private function getHttpClient(): HttpClient
    {
        if (null === $this->httpClient) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    public function getAuthorizeUrl(): string
    {
        return ClientConfigurationInterface::API_URI . 'oauth/authorize?client_id=' . $this->clientId .
            '&redirect_uri=' . $this->redirectUri . '&scope=' . $this->scopeList . '&state=' . $this->getState() .
            '&response_type=code';
    }

    public function setState(string $state): OAuth2
    {
        $this->state = $state;

        return $this;
    }

    public function getState(): string
    {
        if (!isset($this->state)) {
            $this->state = $this->getRandomState();
        }

        return $this->state;
    }

    public function getScopeList(): ScopeList
    {
        return $this->scopeList;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function refreshToken(string $code): ?string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $request = $this->getHttpClient()->sendRequest(
            $this->messageFactory->createRequest(
                'POST',
                self::API_URL . 'token',
                [],
                http_build_query([
                    'code' => $code,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->clientId,
                    'redirect_uri' => $this->redirectUri,
                    'response_type' => 'code',
                ])
            )
        );

        if (200 === $request->getStatusCode()) {
            $body = json_decode($request->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

            $this->accessToken = $body->access_token;

            return $this->accessToken;
        }

        return null;
    }

    private function getRandomState(int $length = 32): string
    {
        // Converting bytes to hex will always double length. Hence, we can reduce
        // the amount of bytes by half to produce the correct length.
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request): RequestInterface
    {
        $header = sprintf('Bearer %s', $this->accessToken);

        return $request->withHeader('Authorization', $header);
    }
}
