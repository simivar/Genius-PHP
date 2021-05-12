<?php
declare(strict_types=1);

namespace Genius\Factory;

use Genius\HttpClient\ClientConfiguration;
use Genius\HttpClient\ClientConfigurationInterface;
use Genius\HttpClient\RequestBuilder;
use Genius\HttpClient\RequestBuilderInterface;
use Genius\HttpClient\Requester;
use Http\Client\Common\PluginClient;

final class RequesterFactory
{
    private static ?ClientConfigurationInterface $clientConfiguration;
    private static ?RequestBuilderInterface $requestBuilder;

    public static function create(): Requester
    {
        return new Requester(self::getHttpClient(), self::getRequestBuilder());
    }

    private static function getHttpClient(): PluginClient
    {
        return self::getClientConfiguration()->createClient();
    }

    public static function setClientConfiguration(ClientConfigurationInterface $clientConfiguration): void
    {
        self::$clientConfiguration = $clientConfiguration;
    }

    private static function getClientConfiguration(): ClientConfigurationInterface
    {
        if (!isset(self::$clientConfiguration)) {
            self::$clientConfiguration = new ClientConfiguration(null);
        }

        return self::$clientConfiguration;
    }

    private static function getRequestBuilder(): RequestBuilderInterface
    {
        if (!isset(self::$requestBuilder)) {
            self::$requestBuilder = new RequestBuilder();
        }

        return self::$requestBuilder;
    }
}
