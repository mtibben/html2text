<?php

namespace Html2Text;

use PHPUnit\Framework\TestCase;

/**
 * Large inline data URIs must not exhaust the PCRE backtrack limit (#130).
 */
class DataUriTest extends TestCase
{
    public function dataUriDataProvider()
    {
        $image = 'data:image/png;base64,' . str_repeat('QUJDREVGR0g', 120000);

        return array(
            'Image in list item' => array(
                'html' => '<ul><li>Item <img src="' . $image . '"></li></ul>',
                'expected' => '* Item',
            ),
            'Image in link' => array(
                'html' => '<a href="https://example.com"><img src="' . $image . '"></a> text',
                'expected' => '[https://example.com] text',
            ),
            'Image with alt tag in paragraph' => array(
                'html' => '<p>Hello <img src="' . $image . '" alt="logo"> world</p>',
                'expected' => 'Hello [logo] world',
            ),
        );
    }

    /**
     * @dataProvider dataUriDataProvider
     */
    public function testDataUri($html, $expected)
    {
        $html2text = new Html2Text($html);
        $this->assertEquals($expected, trim($html2text->getText()));
    }
}
