# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html)

## [3.0.0] - tdb

### Added

- Introduced `Genius\Exception\ApiResponseErrorException`

### Changed

- Object returned by and Exceptions thrown by `Genius\Resources\AbstractResource::sendRequest` changed
  now on `success` it returns data from `result` object preperty
  and on `error` it throws `Genius\Exception\ApiResponseErrorException`. 
  This means that return value and exceptions of **every** `Resource` has changed!
- `Genius\ConnectGenius` is based on PSR-17 Discovery instead of deprecated `Http\Discovery\UriFactoryDiscovery`
- `Genius\ConnectGenius::getUriFactory` returns now `Psr\Http\Message\UriFactoryInterface`
  instead of `Psr\Http\Message\UriInterface`
- `Genius\ConnectGenius::setUriFactory` now takes as first parameter `Psr\Http\Message\UriFactoryInterface`
  instead of `Psr\Http\Message\UriInterface`
- `Genius\Genius::getRequestFactory` returns now `Psr\Http\Message\RequestFactoryInterface` 
  instead of `Http\Message\MessageFactory`
- Moved `Genius\GeniusException` to `Genius\Exception\GeniusException`
- Moved `Genius\ConnectGeniusException` to `Genius\Exception\ConnectGeniusException`
- Moved `Genius\Resources\ResourceException` to `Genius\Exception\ResourceException`

### Removed

- Support for PHP <7.3

## [2.0.0] - 2019-12-27

### Added

- Support for HTTPlug 2.0
- Type-hints everywhere
- Introduced `Genius\GeniusException`

### Changed

- Signature of `Genius\Resources\ArtistsResource::getSongs` changed from array as second argument
  to `getSongs(int $id, string $sort = 'title', ?int $per_page = null, ?int $page = null)`
- Signature of `Genius\Resources\WebPagesResource::get` changed from array as first argument
  to `get(string $raw_annotatable_url, ?string $canonical_url = null, ?string $og_url = null)`
- Signature of `Genius\Resources\AnnotationsResource::post` changed from array as first argument
  to `post(array $annotation, array $referent, array $web_page)`
- Signature of `Genius\Resources\AnnotationsResource::put` changed from array as first argument
  to `put(int $id, array $annotation, array $referent, array $web_page)`
- Signature of `Genius\Resources\ReferentsResource::put` changed from array as first argument
  to `get(?int $created_by_id = null, ?int $song_id = null, ?int $web_page_id = null, string 
  $text_format = 'dom', ?int $per_page = null, ?int $page = null)`
- `Genius\Authentication\OAuth2::getAccessToken` returns `null` instead of `false`
- `Genius\Authentication\OAuth2::refreshToken` returns `null` instead of `false`
- All Exceptions thrown by library are children of `Genius\GeniusException`
- Resources throw `Genius\Resources\ResourceException` when calling a method that requires 
  `scope` and `Bearer` authentication is used
- Exception messages in `Genius\Resources namespace` are now more developer-friendly contain 
  called method name and class

### Removed

- Support for PHP <7.1

## [1.0.1] - 2017-09-13

### Added

- Method `getAvailableScopes` in `Genius\Authentication\Scope`

## [1.0.0] - 2017-07-11

### Added

- oAuth2 support
- HTTPlug abstraction

## 0.1.0 - 2016-11-15

### Added

- Initial release

[3.0.0]: https://github.com/simivar/Genius-PHP/compare/2.0.0...3.0.0
[2.0.0]: https://github.com/simivar/Genius-PHP/compare/1.0.1...2.0.0
[1.0.1]: https://github.com/simivar/Genius-PHP/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/simivar/Genius-PHP/compare/0.1.0...1.0.0