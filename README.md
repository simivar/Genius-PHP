# This is not production ready codebase! This is development branch! Use different branch!

# Genius PHP
Genius PHP is a open source library that allows you to access [Genius API](https://docs.genius.com/) from your PHP application. Right now it supports only `access_token` connection (only `get` methods).

## Getting started
Genius PHP is avialable via [Composer](https://getcomposer.org/). Version 1.0 does not implement HTTP Client on it's own anymore and uses [HTTPlug](http://httplug.io/) abstraction so you are free to choose any HTTP Client you want that depends on [php-http/client-implementation virutal package](https://packagist.org/providers/php-http/client-implementation). 

```
composer require simivar/genius-php php-http/message php-http/guzzle6-adapter
```

## Usage
> **Note:** This version of Genius PHP requires PHP version of 5.6 or higher.

You can use every class by using `->classname` on Genius object. Of course you can create manually every instance of every class if you want to but it's easier to create main `Genius` class - you don't have to put your access token everywhere.

```php
require_once('vendor/autoload.php');

$geniusphp = new \Genius\Genius('access_token');

// let's search for the most popular song on Genius for Kendrick Lamar
$search = $geniusphp->search->get('Kendrick Lamar')->response->hits[0]->result->id;

// and get everything about that song
$song = $geniusphp->songs->get( $search );
```

Complete documentation, installation instructions, and examples are available at: [http://simivar.github.io/Genius-PHP/](http://simivar.github.io/Genius-PHP/).

## Versioning
Genius PHP is created using [Semver](http://semver.org/). All minor and patch updates are backwards compatibile. ``0.1`` branch is no longer maintained.

## License
Please see the [license file](https://github.com/simivar/Genius-PHP/blob/master/LICENSE) for more information.