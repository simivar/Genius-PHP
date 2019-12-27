<?php
declare(strict_types=1);

namespace Genius\Resources;

use Genius\Authentication\Scope;
use Genius\Genius;

class AbstractResource
{
    protected const API_URL = 'https://api.genius.com/';

    /** @var Genius */
    protected $genius;
    
    public function __construct(Genius $genius)
    {
        $this->genius = $genius;
    }
    
    protected function sendRequest(
        string $method,
        string $uri,
        array $headers = [],
        $body = null,
        string $protocolVersion = '1.1'
    ): \stdClass {
        $req =  $this->genius->getRequestFactory()->createRequest($method, self::API_URL . $uri, $headers, $body, $protocolVersion);

        return json_decode($this->genius->getHttpClient()->sendRequest($req)->getBody()->getContents(), false);
    }

    /**
     * @param $scope
     * @return bool
     * @throws ResourceException
     */
    protected function requireScope(string $scope): bool
    {
        /** @var Scope $scopes */
        $scopes = $this->genius->getAuthentication()->getScope();
        if ($scopes->hasScope($scope)) {
            return true;
        }
        
        throw new ResourceException(sprintf('You have no access to required scope %s for that action.', $scope));
    }
}
