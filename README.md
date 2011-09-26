## Instructions

1. Include `bbcode.php`
2. Instantiate the `BBCode` class
3. Execute the `toHTML` method on a string which contains BBCode (optionally pass `true` as the second argument to escape special html chars first)
4. Enjoy HTML output

## Example Code

```php
<?php
require "bbcode.php";

$text=<<<EOF

[b]This is bold text[/b]

[i]This is italic text[/i]

[code]This is pre-formatted text[/code]

[quote]This is a quote[/quote]

[s]This text has a strikethrough[/s]

[u]This text is underlined.[/u]

[center]This text is centered[/center]

[color=red]This is red text[/color]

[url]http://www.google.com/[/url]

[url=http://www.google.com/]Google.com yo![/url]

[img]http://i.imgur.com/WqYEO.jpg[/img]

An image as a link:

[url=http://en.wikipedia.org/wiki/Ninja][img]http://i.imgur.com/8d7Yu.jpg[/img][/url]

This is a list: 

[list]
[*]list item #1
[*]list item #2
[*][b]bold list item #3[/b]
[/list]

[youtube]http://youtu.be/DabwEqsWWiA&hd=1[/youtube]

[youtube]http://www.youtube.com/watch?v=DabwEqsWWiA[/youtube]


EOF;

$bbcode = new BBCode;
echo $bbcode->toHTML($text);  
?>
```

## Example Output

```html

<strong>This is bold text</strong>

<em>This is italic text</em>

<pre><code>This is pre-formatted text</code><pre>

<blockquote><p>This is a quote</p></blockquote>

<del>This text has a strikethrough</del>

<span style="text-decoration:underline;">This text is underlined.</span>

<div style="text-align:center;">This text is centered</div>

<span style="color:red;">This is red text</span>

<a href="http://www.google.com/">http://www.google.com/</a>

<a href="http://www.google.com/">Google.com yo!</a>

<img src="http://i.imgur.com/WqYEO.jpg"/>

An image as a link:

<a href="http://en.wikipedia.org/wiki/Ninja"><img src="http://i.imgur.com/8d7Yu.jpg"/></a>

This is a list: 

<ul><li>list item #1</li><li>list item #2</li><li><strong>bold list item #3</strong></li></ul>

<iframe class="youtube-player" type="text/html" width="640" height="385" src="http://www.youtube.com/embed/DabwEqsWWiA" frameborder="0"></iframe>

<iframe class="youtube-player" type="text/html" width="640" height="385" src="http://www.youtube.com/embed/DabwEqsWWiA" frameborder="0"></iframe>
```