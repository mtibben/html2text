<?php

namespace Html2Text;

class ConstructorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $html = 'Foo';
        $options = array('do_links' => 'none');
        $html2text = new Html2Text($html, $options);
        $this->assertEquals($html, $html2text->getText());

        $html2text = new Html2Text($html);
        $this->assertEquals($html, $html2text->getText());
    }
}
