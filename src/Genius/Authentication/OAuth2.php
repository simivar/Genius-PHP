<?php

declare(strict_types=1);

namespace Genius\Authentication;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;
use Psr\Http\Message\RequestInterface;

final class OAuth2 implements Authentication
{
    private const API_URL = 'https://api.genius.com/oauth/';

    private string $clientSecret;
    private string $clientId;
    private string $state;
    private ?string $accessToken;
    private Scope $scope;
    private HttpClient $httpClient;
    private MessageFactory $messageFactory;
    private string $redirectUri;

    public function __construct(string $clientId, string $clientSecret, string $redirectUri, Scope $scope, ?HttpClient $httpClient = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;

        if (!isset($httpClient)) {
            $this->getHttpClient();
        } else {
            $this->httpClient = $httpClient;
        }
        $this->messageFactory = MessageFactoryDiscovery::find();
        $this->setScope($scope);
    }

    public function setScope(Scope $scope): OAuth2
    {
        $this->scope = $scope;

        return $this;
    }

    public function setClientId(string $clientId): OAuth2
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function setAccessToken(string $access_token): OAuth2
    {
        $this->accessToken = $access_token;

        return $this;
    }

    public function setRedirectUri(string $redirect_uri): OAuth2
    {
        $this->redirectUri = $redirect_uri;

        return $this;
    }

    public function setMessageFactory(MessageFactory $messageFactory): OAuth2
    {
        $this->messageFactory = $messageFactory;

        return $this;
    }

    public function setState(string $state): OAuth2
    {
        $this->state = $state;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        if ($this->hasValidAccessToken()) {
            return $this->accessToken;
        }

        $this->accessToken = null;

        return null;
    }

    private function getHttpClient(): HttpClient
    {
        if (!isset($this->httpClient)) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }

    public function getAuthUrl(): string
    {
        return self::API_URL . 'authorize?client_id=' . $this->clientId .
            '&redirect_uri=' . $this->redirectUri . '&scope=' . $this->scope . '&state=' . $this->getState() .
            '&response_type=code';
    }

    public function getState(): string
    {
        if (null === $this->state) {
            $this->state = $this->getRandomState();
        }

        return $this->state;
    }

    public function getScope(): Scope
    {
        return $this->scope;
    }

    public function hasValidAccessToken(): bool
    {
        return null !== $this->accessToken;
    }

    public function refreshToken(string $code): ?string
    {
        if ($this->getAccessToken()) {
            return $this->getAccessToken();
        }

        $request = $this->httpClient->sendRequest(
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
            $body = json_decode($request->getBody()->getContents(), false);

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
        if ($this->hasValidAccessToken()) {
            $header = sprintf('Bearer %s', $this->accessToken);

            return $request->withHeader('Authorization', $header);
        }

        return $request;
    }
}
