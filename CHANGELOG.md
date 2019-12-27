# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html)

## [2.0.0] - 2019-12-27

### Added

- Support for HTTPlug 2.0
- Type-hints everywhere

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

[2.0.0]: https://github.com/simivar/Genius-PHP/compare/1.0.1...2.0.0
[1.0.1]: https://github.com/simivar/Genius-PHP/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/simivar/Genius-PHP/compare/0.1.0...1.0.0