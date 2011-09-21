## Instructions

1. Include `bbcode.php`
2. Instantiate the `BBCode` class
3. Execute the `toHTML` method on a string which contains BBCode
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

[color=red]This is red text[/color]

[url]http://www.google.com/[/url]

[url=Google.com yo!]http://www.google.com/[/url]

[img]http://i.imgur.com/WqYEO.jpg[/img]

This is a list: 

[list]
[*]list item #1
[*]list item #2
[*][b]list item #3[/b]
[/list]

[youtube]http://youtu.be/DabwEqsWWiA&hd=1[/youtube]

[youtube]http://www.youtube.com/watch?v=DabwEqsWWiA&hd=1[/youtube]


EOF;

$bbcode = new BBCode;
echo $bbcode->toHTML($text);  
?>
```

## Example Output

```html
<strong>This is bold text</strong>

<em>This is italic text</em>

<pre>This is pre-formatted text<pre>

<blockquote><p>This is a quote</p></blockquote>

<span style="color:red;">This is red text</span>

<a href="http://www.google.com/">http://www.google.com/</a>

This is a list: 

<ul><li>list item #1</li><li>list item #2</li><li><strong>list item #3</strong></li></ul>

<iframe class="youtube-player" type="text/html" width="640" height="385"
src="http://www.youtube.com/embed/DabwEqsWWiA?origin=wellplayed.org" frameborder="0"></iframe>

<iframe class="youtube-player" type="text/html" width="640" height="385"
src="http://www.youtube.com/embed/DabwEqsWWiA?origin=wellplayed.org" frameborder="0"></iframe>
```