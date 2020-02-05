<?php
declare(strict_types=1);

namespace Genius\Resources;

use Genius\Authentication\OAuth2;
use Genius\Exception\ApiResponseErrorException;
use Genius\Genius;
use Psr\Http\Client\ClientExceptionInterface;
use stdClass;

class AbstractResource
{
    protected const API_URL = 'https://api.genius.com/';

    protected const OK_STATUS_CODE = 200;

    /** @var Genius */
    protected $genius;
    
    public function __construct(Genius $genius)
    {
        $this->genius = $genius;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @param null $body
     * @param string $protocolVersion
     * @return stdClass
     * @throws ApiResponseErrorException
     * @throw \JsonException
     * @throws ClientExceptionInterface
     */
    protected function sendRequest(
        string $method,
        string $uri,
        array $headers = [],
        $body = null,
        string $protocolVersion = '1.1'
    ): stdClass {
        $req =  $this->genius->getRequestFactory()->createRequest($method, self::API_URL . $uri, $headers, $body, $protocolVersion);

        $response = $this->genius->getHttpClient()->sendRequest($req);
        $decodedBody = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        if ($response->getStatusCode() === self::OK_STATUS_CODE) {
            return $decodedBody->response;
        }

        if (isset($decodedBody->meta)) {
            throw new ApiResponseErrorException($decodedBody->meta->message, $response->getStatusCode());
        }

        throw new ApiResponseErrorException($decodedBody->error_description, $response->getStatusCode());
    }

    /**
     * @param $scope
     * @return bool
     * @throws ResourceException
     */
    protected function requireScope(string $scope): bool
    {
        if (!($this->genius->getAuthentication() instanceof OAuth2)) {
            throw new ResourceException(sprintf(
                '"%s" requires "%s" scope which is available only when using OAuth2 authentication.',
                $this->getCallerClassAndFunctionName(),
                $scope
            ));
        }

        $scopes = $this->genius->getAuthentication()->getScope();
        if ($scopes->hasScope($scope)) {
            return true;
        }
        
        throw new ResourceException(sprintf(
            'You have no access to required scope "%s" for "%s" action.',
            $scope,
            $this->getCallerClassAndFunctionName()
        ));
    }

    private function getCallerClassAndFunctionName(): string
    {
        return debug_backtrace()[2]['class'] . '::' . debug_backtrace()[2]['function'];
    }
}
