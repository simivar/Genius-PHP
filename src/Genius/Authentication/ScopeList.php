<?php

declare(strict_types=1);

namespace Genius\Authentication;

use Genius\Enum\Scope;

class ScopeList
{
    public const SCOPE_ME = 'me';
    public const SCOPE_CREATE_ANNOTATION = 'create_annotation';
    public const SCOPE_MANAGE_ANNOTATION = 'manage_annotation';
    public const SCOPE_VOTE = 'vote';

    protected const SCOPE_SEPARATOR = ' ';

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
        if (in_array($scope, Scope::values(), true)) {
            return true;
        }

        return false;
    }

    public function hasScope(Scope $scope): bool
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
