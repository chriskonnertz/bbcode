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
        $this->assertEquals($html, '<strong>bold</strong>');
    }

    public function testITag()
    {
        $html = $this->render('[i]italic[/i]');
        $this->assertEquals($html, '<em>italic</em>');
    }

    public function testSTag()
    {
        $html = $this->render('[s]deleted[/s]');
        $this->assertEquals($html, '<del>deleted</del>');
    }

    public function testUTag()
    {
        $html = $this->render('[u]underlined[/u]');
        $this->assertEquals($html, '<span style="text-decoration: underline">underlined</span>');
    }

    public function testCodeTag()
    {
        $html = $this->render('[code]code[/code]');
        $this->assertEquals($html, '<pre><code>code</code></pre>');
    }

    public function testEmailTag()
    {
        $html = $this->render('[email]test@example.com[/email]');
        $this->assertEquals($html, '<a href="mailto:test@example.com">test@example.com</a>');
    }

    public function testUrlTag()
    {
        $html = $this->render('[url=http://example.com]Example.com[/url]');
        $this->assertEquals($html, '<a href="http://example.com">Example.com</a>');
    }

    public function testImgTag()
    {
        $html = $this->render('[img]http://example.com/example.png[/img]');
        $this->assertEquals($html, '<img src="http://example.com/example.png" />');
    }

    public function testQuoteTag()
    {
        $html = $this->render('[quote]Hello world![/quote]');
        $this->assertEquals($html, '<blockquote>Hello world!</blockquote>');
    }

    public function testFontTag()
    {
        $html = $this->render('[font=Arial]Hello world![/font]');
        $this->assertEquals($html, '<span style="font-family: Arial">Hello world!</span>');
    }

    public function testSizeTag()
    {
        $html = $this->render('[size=12]Hello world![/size]');
        $this->assertEquals($html, '<span style="font-size: 12%">Hello world!</span>');
    }

    public function testColorTag()
    {
        $html = $this->render('[color=red]Hello world![/color]');
        $this->assertEquals($html, '<span style="color: red">Hello world!</span>');
    }

    public function testLeftTag()
    {
        $html = $this->render('[left]Hello world![/left]');
        $this->assertEquals($html, '<div style="text-align: left">Hello world!</div>');
    }

    public function testCenterTag()
    {
        $html = $this->render('[center]Hello world![/center]');
        $this->assertEquals($html, '<div style="text-align: center">Hello world!</div>');
    }

    public function testRightTag()
    {
        $html = $this->render('[right]Hello world![/right]');
        $this->assertEquals($html, '<div style="text-align: right">Hello world!</div>');
    }

    public function testSpoilerTag()
    {
        $html = $this->render('[spoiler]Spoiler[/spoiler]');
        $this->assertEquals($html, '<div class="spoiler">Spoiler</div>');
    }

    public function testMultiByte()
    {
        $bbCode = '[b]We ❤❤❤❤❤❤❤❤❤❤❤ BBCode[/b]';

        $html = $this->render($bbCode);
        $this->assertEquals($html, '<strong>We ❤❤❤❤❤❤❤❤❤❤❤ BBCode</strong>');
    }

    public function testToString()
    {
        $bbCode = $this->getInstance('[b]bold[/b]');

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

}