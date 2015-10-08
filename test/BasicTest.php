<?php

namespace Html2Text;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function basicDataProvider() {
        return array(
            'Readme usage' => array(
                'html'      => 'Hello, &quot;<b>world</b>&quot;',
                'expected'  => 'Hello, "WORLD"',
            ),
            'No stripslashes on HTML content' => array(
                // HTML content does not escape slashes, therefore nor should we.
                'html'      => 'Hello, \"<b>world</b>\"',
                'expected'  => 'Hello, \"WORLD\"',
            ),
            'Zero is not empty' => array(
                'html'      => '0',
                'expected'  => '0',
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
