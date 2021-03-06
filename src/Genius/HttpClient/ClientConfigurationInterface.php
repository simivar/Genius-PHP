<?php

declare(strict_types=1);

namespace Genius\HttpClient;

use Http\Client\Common\PluginClient;
use Http\Message\Authentication;

interface ClientConfigurationInterface
{
    public const API_URI = 'https://api.genius.com/';

    public function __construct(Authentication $authentication);

    public function createClient(): PluginClient;
}
