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

[youtube]http://www.youtube.com/watch?v=DabwEqsWWiA[/youtube]


EOF;

$bbcode = new BBCode;
echo $bbcode->toHTML($text);  
?>