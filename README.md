# Genius PHP
Genius PHP is a open source library that allows you to access [Genius API](https://docs.genius.com/) from your PHP application. It supports oAuth 2 from version 1.0.

## Getting started
Genius PHP is avialable via [Composer](https://getcomposer.org/). Version 1.0 does not implement HTTP Client on it's own anymore and uses [HTTPlug](http://httplug.io/) abstraction so you are free to choose any HTTP Client you want that depends on [php-http/client-implementation virutal package](https://packagist.org/providers/php-http/client-implementation). 

```
composer require simivar/genius-php php-http/message php-http/guzzle6-adapter
```

## Usage
You can use every resource available with classes from *Resources* namespace. You can create main `Genius` object and use getters or create every object on your own.

```php
require_once('vendor/autoload.php');

$authentication = new \Genius\Authentication\OAuth2(
  'YOUR_CLIENT_ID',
  'YOUR_CLIENT_SECRET',
  'YOUR_REDIRECT_URL',
  new \Genius\Authentication\ScopeList([
      \Genius\Enum\Scope::ME(),
      \Genius\Enum\Scope::CREATE_ANNOTATION(),
      \Genius\Enum\Scope::MANAGE_ANNOTATION(),
      \Genius\Enum\Scope::VOTE(),
  ]),
  null
);

$accessTokenFromDatabase = 'get access token from DataBase here';
if ($accessTokenFromDatabase === null) {
    $authorizeUrl = $authentication->getAuthorizeUrl();
    // redirect user to $authorizeUrl
} else {
    $authentication->setAccessToken($accessTokenFromDatabase);
}

$genius = new \Genius\Genius($authentication);
$upvoteAnnotation = $genius->getAnnotationsResource()->get(11852248);
```

Complete documentation, installation instructions, and examples are available at: [http://simivar.github.io/Genius-PHP/](http://simivar.github.io/Genius-PHP/).

## Versioning
Genius PHP is created using [Semver](http://semver.org/). All minor and patch updates are backwards compatibile. ``0.1`` branch is no longer maintained.

## License
Please see the [license file](https://github.com/simivar/Genius-PHP/blob/master/LICENSE) for more information.