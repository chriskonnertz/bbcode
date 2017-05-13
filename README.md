
# BBCode

A library that parser BBCode and converts it to HTML code. Written in PHP.

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

Here is a minimalistic example of PHP code that uses this libary. It assumes that there is an autoloader.

```
$bbcode = new ChrisKonnertz\BBCode\BBCode();

$rendered = $bbcode->render('[b]Hello world![/b]');

echo $rendered;
```

## Supported Tags

* [b] Bold
* [i] Italic
* [s] Struck through
* [u] Underlined
* [code] Code
* [email Email
* [url] URL
* [img] Image
* [\*] List item
* [li] List item
* [quote] Quote
* [youtube] YouTube video
* [font] Font
* [size] Font size
* [color] Font color
* [left] Text-align: left
* [center] Text-align: center
* [right] Text-align: right
* [spoiler] Spiler (just HTML code that needs JavaScript code have a function)

You can add custom tags with the `addTag($name, Closure $closure)` method.

## Helpful methods

* `renderRaw($text = null)`: Renders only the text without any tags
* `addTag($name, Closure $closure)`: Adds a custom tag (with name and a Closure)
* `forgetTag($name)`: Remove the tag with the given name
* `ignoreTag($name)`: Add a tag to the array of ignored tags
* `permitTag($name)`: Remove a tag from the array of ignored tags

Note that the `BBCode` class implements the `__toString()` method, which internally calls the `render()` method.

## Fork

This repository originally has been forked from [kaimallea/php-bbcode](https://github.com/kaimallea/php-bbcode). However, it has been completely rewritten.

## Status

Status of this repository: **Maintained**. Create an issue and you will get a response, usually within 48 hours.
