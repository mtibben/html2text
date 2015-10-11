<?php

namespace Html2Text;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testShowAltText()
    {
        $html = new Html2Text('<img id="head" class="header" src="imgs/logo.png" alt="This is our cool logo" />');

        $this->assertEquals('image: "This is our cool logo"', $html->getText());
    }
}
