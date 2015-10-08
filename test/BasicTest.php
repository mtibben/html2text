<?php

namespace Html2Text;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function basicDataProvider() {
        return array(
            'Usage in Readme' => array(
                'html'      => 'Hello, &quot;<b>world</b>&quot;',
                'expected'  => 'Hello, "WORLD"',
            ),
            'No stripslashes on HTML content' => array(
                // HTML content does not escape slashes, therefore nor should we.
                'html'      => 'Hello, \"<b>world</b>\"',
                'expected'  => 'Hello, \"WORLD\"',
            ),
        );
    }

    /**
     * @dataProvider basicDataProvider
     */
    public function testBasic($html, $expected)
    {
        $html = new Html2Text($html);
        $this->assertEquals($expected, $html->getText());
    }
}
