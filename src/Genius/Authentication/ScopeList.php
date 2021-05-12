<?php

declare(strict_types=1);

namespace Genius\Authentication;

use Genius\Enum\Scope;

final class ScopeList
{
    private const SCOPE_SEPARATOR = ' ';

    /**
     * @var Scope[]
     */
    private array $scope = [];

    /**
     * @param Scope[] $scopes
     */
    public function __construct(array $scopes = [])
    {
        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }

    public function addScope(Scope $scope): self
    {
        if ($this->isValidScope($scope)) {
            $this->scope[(string) $scope] = $scope;
        }

        return $this;
    }

    public function removeScope(Scope $scope): self
    {
        if ($this->isValidScope($scope) && $this->hasScope($scope)) {
            unset($this->scope[(string) $scope]);
        }

        return $this;
    }

    public function isValidScope(Scope $scope): bool
    {
        if (Scope::isValid($scope->getValue())) {
            return true;
        }

        return false;
    }

    public function hasScope(Scope $scope): bool
    {
        if (array_key_exists((string) $scope, $this->scope)) {
            return true;
        }

        return false;
    }

    public function __toString()
    {
        return implode(self::SCOPE_SEPARATOR, $this->scope);
    }
}
