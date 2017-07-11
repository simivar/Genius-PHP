<?php
/**
 * Created by PhpStorm.
 * User: simivar
 * Date: 2017-06-18
 * Time: 21:22
 */

namespace Genius\Resources;

use Genius\Genius;

class AbstractResource
{
    
    const API_URL = 'https://api.genius.com/';
    
    protected $genius;
    
    public function __construct(Genius $genius)
    {
        $this->genius = $genius;
    }
    
    protected function sendRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        $req =  $this->genius->getRequestFactory()->createRequest($method, self::API_URL . $uri, $headers, $body, $protocolVersion);
    
        return json_decode($this->genius->getHttpClient()->sendRequest($req)->getBody());
    }
    
    protected function requireScope($scope)
    {
        $scopes = $this->genius->getAuthentication()->getScope();
        
        if ($scopes->hasScope($scope)) {
            return true;
        }
        
        throw new ResourceException(sprintf('You have no access to required scope %s for that action.', $scope));
    }
    
}
