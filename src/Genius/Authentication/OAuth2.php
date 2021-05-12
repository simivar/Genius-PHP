<?php

declare(strict_types=1);

namespace Genius\Authentication;

use Genius\HttpClient\ClientConfiguration;
use Genius\HttpClient\ClientConfigurationInterface;
use Genius\HttpClient\RequestBuilder;
use Genius\HttpClient\Requester;
use Genius\HttpClient\RequesterInterface;
use Http\Message\Authentication;
use JsonException;
use Psr\Http\Message\RequestInterface;

final class OAuth2 implements Authentication
{
    private string $clientSecret;
    private string $clientId;
    private string $state;
    private ?string $accessToken;
    private ScopeList $scopeList;
    private RequesterInterface $requester;
    private string $redirectUri;

    public function __construct(string $clientId, string $clientSecret, string $redirectUri, ScopeList $scope)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->requester = new Requester(
            (new ClientConfiguration(null))->createClient(), new RequestBuilder(),
        );
        $this->scopeList = $scope;
    }

    public function setAccessToken(string $accessToken): OAuth2
    {
        $this->accessToken = $accessToken;

        return $this;
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
     * @throws \Genius\Exception\ApiResponseErrorException
     * @throws JsonException
     */
    public function refreshToken(string $code): string
    {
        if (isset($this->accessToken)) {
            return $this->accessToken;
        }

        $response = $this->requester->post('oauth/token', [
            'code' => $code,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
        ]);
        $this->accessToken = $response->access_token;

        return $this->accessToken;
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
