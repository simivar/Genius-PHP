<?php

declare(strict_types=1);

namespace Genius\Resources;

use Genius\HttpClient\RequesterInterface;

abstract class AbstractResource
{
    protected RequesterInterface $requester;

    public function __construct(RequesterInterface $requester)
    {
        $this->requester = $requester;
    }

    protected function requireScope(string $scope): bool
    {
        return true;
        /*if (!($this->genius->getAuthentication() instanceof OAuth2)) {
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
        ));*/
    }

    private function getCallerClassAndFunctionName(): string
    {
        return debug_backtrace()[2]['class'] . '::' . debug_backtrace()[2]['function'];
    }
}
