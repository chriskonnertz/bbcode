# BBCode

[![Build Status](https://img.shields.io/travis/chriskonnertz/bbcode.svg)](https://travis-ci.org/chriskonnertz/bbcode)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/chriskonnertz/bbcode/master/LICENSE)
[![Version](https://img.shields.io/packagist/v/chriskonnertz/bbcode.svg)](https://packagist.org/packages/chriskonnertz/bbcode)

A library that parses BBCode and converts it to HTML code. Written in PHP.

## Installation

Through Composer:

```
composer require chriskonnertz/bbcode
```

From then on you may run `composer update` to get the latest version of this library.

It is possible to use this library without using Composer but then it is necessary to register an 
[autoloader function](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md#example-implementation).

> This library requires PHP 5.5 or higher.

## Usage example

Here is a minimalistic example of PHP code that uses this library. It assumes that there is an autoloader.

```php
$bbcode = new ChrisKonnertz\BBCode\BBCode();

$rendered = $bbcode->render('[b]Hello world![/b]');

echo $rendered;
```

## Available tags

* `[b]`: Font style bold - `[b]Hello world[/b]`
* `[i]`: Font style italic - `[i]Hello world[/i]`
* `[s]`: Font style struck through - `[s]Hello world[/s]`
* `[u]`: Font style underlined - `[u]Hello world[/u]`
* `[code]`: Code - `[code]Hello world[/code]`
* `[email]`: Email (clickable) - `[email]test@example.com[/email]`
* `[url]`: URL (clickable) - `[url=http://example.com]Example.com[/url]`
* `[img]`: Image (not clickable) - `[img]http://example.com/example.png[/img]`
* `[quote]`: Quote - `[quote]Hello world[/quote]`
* `[youtube]`: Embedded YouTube video - `[youtube]a-video-id-123456[/youtube]`
* `[font]`: Font (name) - `[font=Arial]Hello world![/font]`
* `[size]`: Font size - `[size=12]Hello world![/size]`
* `[color]`: Font color - `[color=red]Hello world![/color]`
* `[left]`: Text-align: left - `[left]Hello world[/left]`
* `[center]`: Text-align: center - `[center]Hello world[/center]`
* `[right]`: Text-align: right - `[right]Hello world[/right]`
* `[spoiler]`: Spoiler (pure HTML code that needs JavaScript code to add behaviour) - `[spoiler]Hello world[/spoiler]`
* List:
  *  `[list]`: List - `[list]...list items...[/list]`
  * `[*]`: List item within a list - `[*]Hello world!\r\n[*]Hello moon!`
  * `[li]`: List item within a list (alias) - `[li]Hello world!\r\n[/li][li]Hello moon!/[li]`
  * Full example: `[list][*]Hello world![li]Hello moon![/li][/list]`

You can add custom tags with the `addTag($name, Closure $closure)` method.

## Helpful methods

* `renderRaw($text = null)`: Renders only the text without any tags
* `addTag($name, Closure $closure)`: Adds a custom tag (with name and a Closure)
* `forgetTag($name)`: Remove the tag with the given name
* `ignoreTag($name)`: Add a tag to the array of ignored tags
* `permitTag($name)`: Remove a tag from the array of ignored tags

The `BBCode` class also implements the `__toString()` method, which internally calls the `render()` method.

## Custom tag example

This code will add a `[h1]` BBCode tag that creates a `<h1>` HTML tag:

```php
$bbcode->addTag('h1', function($tag, &$html, $openingTag) {
    if ($tag->opening) {
        return '<h1>';
    } else {
        return '</h1>';
    }
});
```

## Multibyte characters

This library supports the use of multibyte characters.

## Fork

This repository originally has been forked from [kaimallea/php-bbcode](https://github.com/kaimallea/php-bbcode). However, it has been completely rewritten since then.

## Status

Status of this repository: **Maintained**. Create an issue and you will get a response, usually within 48 hours.
