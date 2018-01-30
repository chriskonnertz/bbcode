<?php

use ChrisKonnertz\BBCode\BBCode;

class BBCodeTest extends PHPUnit_Framework_TestCase
{

    /**
     * Creates a new instance of the main class and returns it
     *
     * @return BBCode
     */
    protected function getInstance()
    {
        return new BBCode();
    }

    /**
     * Renders text with BBCode as HTML code and returns it
     *
     * @param string $text
     * @return string
     */
    protected function render($text)
    {
        $bbcode = $this->getInstance();

        return $bbcode->render($text);
    }

    public function testBTag()
    {
        $html = $this->render('[b]bold[/b]');
        $this->assertEquals('<strong>bold</strong>', $html);
    }

    public function testITag()
    {
        $html = $this->render('[i]italic[/i]');
        $this->assertEquals('<em>italic</em>', $html);
    }

    public function testSTag()
    {
        $html = $this->render('[s]deleted[/s]');
        $this->assertEquals('<del>deleted</del>', $html);
    }

    public function testUTag()
    {
        $html = $this->render('[u]underlined[/u]');
        $this->assertEquals('<span style="text-decoration: underline">underlined</span>', $html, );
    }

    public function testCodeTag()
    {
        $html = $this->render('[code]code[/code]');
        $this->assertEquals('<pre><code>code</code></pre>', $html);
    }

    public function testEmailTag()
    {
        $html = $this->render('[email]test@example.com[/email]');
        $this->assertEquals('<a href="mailto:test@example.com">test@example.com</a>', $html);
    }

    public function testUrlTag()
    {
        $html = $this->render('[url=http://example.com]Example.com[/url]');
        $this->assertEquals('<a href="http://example.com">Example.com</a>', $htm);
    }

    public function testImgTag()
    {
        $html = $this->render('[img]http://example.com/example.png[/img]');
        $this->assertEquals('<img src="http://example.com/example.png" />', $html);
    }

    public function testQuoteTag()
    {
        $html = $this->render('[quote]Hello world![/quote]');
        $this->assertEquals('<blockquote>Hello world!</blockquote>', $html);
    }

    public function testFontTag()
    {
        $html = $this->render('[font=Arial]Hello world![/font]');
        $this->assertEquals('<span style="font-family: Arial">Hello world!</span>', $html);
    }

    public function testSizeTag()
    {
        $html = $this->render('[size=12]Hello world![/size]');
        $this->assertEquals('<span style="font-size: 12%">Hello world!</span>', $html);
    }

    public function testColorTag()
    {
        $html = $this->render('[color=red]Hello world![/color]');
        $this->assertEquals('<span style="color: red">Hello world!</span>', $html);
    }

    public function testLeftTag()
    {
        $html = $this->render('[left]Hello world![/left]');
        $this->assertEquals('<div style="text-align: left">Hello world!</div>', $html);
    }

    public function testCenterTag()
    {
        $html = $this->render('[center]Hello world![/center]');
        $this->assertEquals('<div style="text-align: center">Hello world!</div>', $html);
    }

    public function testRightTag()
    {
        $html = $this->render('[right]Hello world![/right]');
        $this->assertEquals('<div style="text-align: right">Hello world!</div>', $html);
    }

    public function testListTags()
    {
        $html = $this->render('[list][*]Hello world![li]Hello moon![/li][/list]');
        $this->assertEquals('<ul><li>Hello world!</li><li>Hello moon!</li></ul>', $html);
    }

    public function testSpoilerTag()
    {
        $html = $this->render('[spoiler]Spoiler[/spoiler]');
        $this->assertEquals('<div class="spoiler">Spoiler</div>', $html);
    }

    public function testMultiByte()
    {
        $bbCode = '[b]We ❤❤❤❤❤❤❤❤❤❤❤ BBCode[/b]';

        $html = $this->render($bbCode);
        $this->assertEquals('<strong>We ❤❤❤❤❤❤❤❤❤❤❤ BBCode</strong>', $html);
    }

    public function testSetText()
    {
        $bbCode = $this->getInstance();

        $bbCode->setText('[b]bold[/b]');

        $this->assertEquals('<strong>bold</strong>', $bbCode->render());
    }

    public function testRenderPlain()
    {
        $bbCode = $this->getInstance();

        $bbCode->setText('[b]bold[/b]');

        $this->assertEquals('bold', $bbCode->renderPlain());
    }

    public function testRenderRaw()
    {
        $bbCode = $this->getInstance();

        $bbCode->setText('[b]bold[/b]');

        $this->assertEquals('bold', $bbCode->renderRaw());
    }

    public function testToString()
    {
        $bbCode = $this->getInstance();

        $bbCode->setText('[b]bold[/b]');

        $this->assertEquals($bbCode->render(), (string) $bbCode);
    }

    public function testCustomTagDefinitions()
    {
        $bbCode = $this->getInstance();

        $customTagName = 'example';

        $bbCode->addTag($customTagName, function($tag, &$html, $openingTag) {
             if ($tag->opening) {
                 return '<span class="example">';
             } else {
                 return '</span>';
             }
        });

        $bbCode->forgetTag($customTagName);

        $this->assertEquals($bbCode->render(), (string) $bbCode);
    }

    public function testIgnoringTags()
    {
        $bbCode = $this->getInstance();

        $bbCode->ignoreTag(BBCode::TAG_NAME_B);
        $ignoredTags = $bbCode->getIgnoredTags();

        $this->assertEquals([BBCode::TAG_NAME_B], $ignoredTags);

        $bbCode->permitTag(BBCode::TAG_NAME_B);
        $ignoredTags = $bbCode->getIgnoredTags();

        $this->assertEquals([], $ignoredTags);
    }

    public function testYouTubeSizeModifiers()
    {
        $bbCode = $this->getInstance();

        $width = $bbCode->getYouTubeWidth() + 1;
        $height = $bbCode->getYouTubeHeight() + 1;

        $bbCode->setYouTubeWidth($width);
        $bbCode->setYouTubeHeight($height);

        $this->assertEquals($width, $bbCode->getYouTubeWidth());
        $this->assertEquals($height, $bbCode->getYouTubeHeight());
    }

    public function testGetDefaultTagNames()
    {
        $bbCode = $this->getInstance();

        $this->assertEquals(20, $bbCode->getDefaultTagNames());
    }

}
