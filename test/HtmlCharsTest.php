<?php

namespace Html2Text;

class HtmlCharsTest extends \PHPUnit_Framework_TestCase
{
    public function testLaquoAndRaquo()
    {
        $html = 'This library name is &laquo;Html2Text&raquo;';
        $expected = 'This library name is «Html2Text»';

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }
}
