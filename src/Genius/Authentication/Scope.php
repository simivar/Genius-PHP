<?php

declare(strict_types=1);

namespace Genius\Authentication;

class Scope
{
    public const SCOPE_ME = 'me';
    public const SCOPE_CREATE_ANNOTATION = 'create_annotation';
    public const SCOPE_MANAGE_ANNOTATION = 'manage_annotation';
    public const SCOPE_VOTE = 'vote';

    protected const SCOPE_SEPARATOR = ' ';

    /**
     * @var string[]
     */
    private array $scope = [];

    /**
     * @param string[] $scopes
     */
    public function __construct(array $scopes = [])
    {
        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }

    public function addScope(string $scope): self
    {
        if ($this->isValidScope($scope)) {
            $this->scope[$scope] = $scope;
        }

        return $this;
    }

    public function removeScope(string $scope): self
    {
        if ($this->isValidScope($scope) && $this->hasScope($scope)) {
            unset($this->scope[$scope]);
        }

        return $this;
    }

    public function isValidScope(string $scope): bool
    {
        if (in_array($scope, self::getAvailableScopes(), true)) {
            return true;
        }

        return false;
    }

    public function hasScope(string $scope): bool
    {
        if (in_array($scope, $this->scope, true)) {
            return true;
        }

        return false;
    }

    /**
     * @return string[]
     */
    public static function getAvailableScopes(): array
    {
        return [
            self::SCOPE_ME,
            self::SCOPE_CREATE_ANNOTATION,
            self::SCOPE_MANAGE_ANNOTATION,
            self::SCOPE_VOTE,
        ];
    }

    public function __toString()
    {
        return implode(self::SCOPE_SEPARATOR, $this->scope);
    }
}
