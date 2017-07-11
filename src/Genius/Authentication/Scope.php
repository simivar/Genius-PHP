<?php

namespace Genius\Authentication;

/**
 * Class Scope thanks to which we know what scope request from API.
 *
 * @package Genius\Authentication
 */
class Scope
{

    const SCOPE_ME = 'me';
    const SCOPE_CREATE_ANNOTATION = 'create_annotation';
    const SCOPE_MANAGE_ANNOTATION = 'manage_annotation';
    const SCOPE_VOTE = 'vote';

    const SCOPE_SEPARATOR = ' ';

    /**
     * @var array
     */
    private $scope = [];

    /**
     * @var array All Genius API available scopes.
     */
    private $availableScopes = [
        self::SCOPE_ME,
        self::SCOPE_CREATE_ANNOTATION,
        self::SCOPE_MANAGE_ANNOTATION,
        self::SCOPE_VOTE,
    ];
    
    /**
     * Scope constructor.
     *
     * @param array $scopes
     */
    public function __construct(array $scopes = [])
    {
        foreach($scopes as $scope){
            $this->addScope($scope);
        }
    }
    
    /**
     * Adds scope.
     *
     * @param string $scope
     *
     * @return $this
     */
    public function addScope($scope)
    {
        if($this->isValidScope($scope)){
            $this->scope[$scope] = $scope;
        }

        return $this;
    }
    
    /**
     * Removes already set scope.
     *
     * @param string $scope
     *
     * @return $this
     */
    public function removeScope($scope)
    {
        if($this->isValidScope($scope) && $this->hasScope($scope)){
            unset($this->scope[$scope]);
        }

        return $this;
    }
    
    /**
     * Checks if scope is valid.
     *
     * @param string $scope
     *
     * @return bool
     */
    public function isValidScope($scope)
    {
        if(in_array($scope, $this->availableScopes)){
            return true;
        }

        return false;
    }
    
    /**
     * Checks if scope was already set.
     *
     * @param $scope
     *
     * @return bool
     */
    public function hasScope($scope)
    {
        if(in_array($scope, $this->scope)){
            return true;
        }

        return false;
    }

    public function __toString()
    {
        return implode(self::SCOPE_SEPARATOR, $this->scope);
    }

}
