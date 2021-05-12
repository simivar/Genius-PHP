<?php

declare(strict_types=1);

namespace Genius\Resources;

use Genius\Enum\Scope;
use Genius\HttpClient\RequesterInterface;

abstract class AbstractResource
{
    protected RequesterInterface $requester;

    public function __construct(RequesterInterface $requester)
    {
        $this->requester = $requester;
    }
}
