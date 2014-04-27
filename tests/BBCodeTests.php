<?php

class BBCodeTest extends PHPUnit_Framework_TestCase
{
    
    protected function getInstance()
    {
        return new ChrisKonnertz\BBCode\BBCode();
    }

    protected function render($text)
    {
        $bbcode = $this->getInstance();
        return $bbcode->render($text);
    }

    public function testB()
    {
        $html = $this->render('[b]bold[/b]');
        $this->assertEquals($html, '<strong>bold</strong>');
    }

    public function testU()
    {
        $html = $this->render('[u]underline[/u]');
        $this->assertEquals($html, '<span style="text-decoration: underline">underline</span>');
    }

}