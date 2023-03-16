<?php

namespace Html2Text;

use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testImageDataWithAltProvider() {
        $this->expectNotToPerformAssertions();
        return array(
            'Without alt tag' => array(
                'html' => '<img src="http://example.com/example.jpg">',
                'expected'  => '',
            ),
            'Without alt tag, wrapped in text' => array(
                'html' => 'xx<img src="http://example.com/example.jpg">xx',
                'expected'  => 'xxxx',
            ),
            'With alt tag' => array(
                'html' => '<img src="http://example.com/example.jpg" alt="An example image">',
                'expected'  => '[An example image]',
            ),
            'With alt, and title tags' => array(
                'html' => '<img src="http://example.com/example.jpg" alt="An example image" title="Should be ignored">',
                'expected'  => '[An example image]',
            ),
            'With alt tag, wrapped in text' => array(
                'html' => 'xx<img src="http://example.com/example.jpg" alt="An example image">xx',
                'expected'  => 'xx[An example image]xx',
            ),
            'With italics' => array(
                'html' => '<img src="shrek.jpg" alt="the ogrelord" /> Blah <i>blah</i> blah',
                'expected' => '[the ogrelord] Blah _blah_ blah'
            )
        );
    }

    public function testImageDataWithoutAltProvider() {
        $this->expectNotToPerformAssertions();
        return array(
            'Without alt tag' => array(
                'html' => '<img src="http://example.com/example.jpg">',
                'expected'  => '',
            ),
            'Without alt tag, wrapped in text' => array(
                'html' => 'xx<img src="http://example.com/example.jpg">xx',
                'expected'  => 'xxxx',
            ),
            'With alt tag' => array(
                'html' => '<img src="http://example.com/example.jpg" alt="An example image">',
                'expected'  => '',
            ),
            'With alt, and title tags' => array(
                'html' => '<img src="http://example.com/example.jpg" alt="An example image" title="Should be ignored">',
                'expected'  => '',
            ),
            'With alt tag, wrapped in text' => array(
                'html' => 'xx<img src="http://example.com/example.jpg" alt="An example image">xx',
                'expected'  => 'xxxx',
            ),
            'With italics' => array(
                'html' => '<img src="shrek.jpg" alt="the ogrelord" /> Blah <i>blah</i> blah',
                'expected' => ' Blah _blah_ blah'
            )
        );
    }

    /**
     * @dataProvider testImageDataWithAltProvider
     */
    public function testImagesAlt($html, $expected)
    {
        $html2text = new Html2Text($html);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }

    /**
     * @dataProvider testImageDataWithoutAltProvider
     */
    public function testImagesNoAlt($html, $expected)
    {
        $html2text = new Html2Text($html,array('alt_image' => 'hide'));
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }
}
