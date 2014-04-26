<?php
require "bbcode.php";

$text=<<<EOF

[b]This is bold text[/b]

[i]This is italic text[/i]

[code]This is pre-formatted text[/code]

[quote]This is a quote[/quote]

[quote="Obama"]This is a quote by a specific person[/quote]

[size=30]This text's size is set at 30%[/size]

[s]This text has a strikethrough[/s]

[u]This text is underlined.[/u]

[center]This text is centered[/center]

[color=red]This is red text[/color]

[email]someone@somewhere.com[/email]

[email=someone@somewhere.com]An e-mail link[/email]

[url]http://www.google.com/[/url]

[url=http://www.google.com/]Google.com yo![/url]

[img]http://i.imgur.com/WqYEO.jpg[/img]

An image as a link:

[url=http://en.wikipedia.org/wiki/Ninja][img]http://i.imgur.com/8d7Yu.jpg[/img][/url]

This is an unordered list: 

[list]
[*]list item #1
[*]list item #2
[*][b]bold list item #3[/b]
[/list]

This is an ordered (numbered) list: 

[list=1]
[*]list item #1
[*]list item #2
[*][b]bold list item #3[/b]
[/list]

This is an ordered (alpha) list: 

[list=a]
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