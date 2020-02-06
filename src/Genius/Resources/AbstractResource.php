<?php
declare(strict_types=1);

namespace Genius\Resources;

use Genius\Authentication\OAuth2;
use Genius\Genius;
use Genius\HttpClient\Requester;
use Genius\Exception\ResourceException;

class AbstractResource
{
    /** @var Requester */
    protected $requester;

    /** @var Genius */
    protected $genius;

    public function __construct(Genius $genius)
    {
        $this->genius = $genius;
        $this->requester = new Requester($genius->getHttpClient());
        $this->requester->setGenius($genius);
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
