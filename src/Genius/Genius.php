<?php

declare(strict_types=1);

namespace Genius;

use Genius\Factory\RequesterFactoryTrait;
use Http\Message\Authentication;

final class Genius
{
    use RequesterFactoryTrait;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function getAccountResource(): Resources\AccountResource
    {
        return new Resources\AccountResource($this->getRequester());
    }

    public function getAnnotationsResource(): Resources\AnnotationsResource
    {
        return new Resources\AnnotationsResource($this->getRequester());
    }

    public function getArtistsResource(): Resources\ArtistsResource
    {
        return new Resources\ArtistsResource($this->getRequester());
    }

    public function getReferentsResource(): Resources\ReferentsResource
    {
        return new Resources\ReferentsResource($this->getRequester());
    }

    public function getSearchResource(): Resources\SearchResource
    {
        return new Resources\SearchResource($this->getRequester());
    }

    public function getSongsResource(): Resources\SongsResource
    {
        return new Resources\SongsResource($this->getRequester());
    }

    public function getWebPagesResource(): Resources\WebPagesResource
    {
        return new Resources\WebPagesResource($this->getRequester());
    }
}
