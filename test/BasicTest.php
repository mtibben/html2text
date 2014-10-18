<?php

namespace Html2Text;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function testBasicUsageInReadme()
    {
        $html = new Html2Text('Hello, &quot;<b>world</b>&quot;');

        $this->assertEquals('Hello, "WORLD"', $html->getText());
    }
}
