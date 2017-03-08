# Genius PHP [![SensioLabsInsight](https://insight.sensiolabs.com/projects/030b82ab-b32c-4ac2-9fa4-e3162017e68e/mini.png)](https://insight.sensiolabs.com/projects/030b82ab-b32c-4ac2-9fa4-e3162017e68e)
Genius PHP is a open source library that allows you to access [Genius API](https://docs.genius.com/) from your PHP application. Right now it supports only `access_token` connection (only `get` methods).

## Getting started
To get started download `autoload.php` with `src` directory and put it together. Then include `autoload.php` in your PHP file - now you can use every class from `src` directory!

## Usage
> **Note:** This version of Genius PHP requires PHP version of 5.3 or higher.

Thanks to autoloader you don't have to manually include all the classes, just the autoloader. Then after creating new instance of `Genius` class you can use every other just by using `->classname`. Of course you can create manually every instance of every class if you want to but it's easier to create main `Genius` class - you don't have to put your access token everywhere.

```php
require_once('autoload.php');

$geniusphp = new \Genius\Genius('access_token');

// let's search for the most popular song on Genius for Kendrick Lamar
$search = $geniusphp->search->get('Kendrick Lamar')->response->hits[0]->result->id

// and get everything about that song
$song = $geniusphp->songs->get( $search );
```

Complete documentation, installation instructions, and examples are available at: [http://simivar.github.io/Genius-PHP/](http://simivar.github.io/Genius-PHP/)

## License
Please see the [license file](https://github.com/simivar/Genius-PHP/blob/master/LICENSE) for more information.
