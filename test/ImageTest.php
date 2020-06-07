<?php

namespace Html2Text;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testImageDataProvider() {
        return array(
            'Without alt tag' => array(
                'html' => '<img src="http://example.com/example.jpg">',
                'expected'  => '',
                'expectedNoImage' => '',
            ),
            'Without alt tag, wrapped in text' => array(
                'html' => 'xx<img src="http://example.com/example.jpg">xx',
                'expected'  => 'xxxx',
                'expectedNoImage' => 'xxxx',
            ),
            'With alt tag' => array(
                'html' => '<img src="http://example.com/example.jpg" alt="An example image">',
                'expected'  => '[An example image]',
                'expectedNoImage' => '',
            ),
            'With alt, and title tags' => array(
                'html' => '<img src="http://example.com/example.jpg" alt="An example image" title="Should be ignored">',
                'expected'  => '[An example image]',
                'expectedNoImage' => '',
            ),
            'With alt tag, wrapped in text' => array(
                'html' => 'xx<img src="http://example.com/example.jpg" alt="An example image">xx',
                'expected'  => 'xx[An example image]xx',
                'expectedNoImage' => 'xxxx',
            ),
            'With italics' => array(
                'html' => '<img src="shrek.jpg" alt="the ogrelord" /> Blah <i>blah</i> blah',
                'expected' => '[the ogrelord] Blah _blah_ blah',
                'expectedNoImage' => ' Blah _blah_ blah',
            )
        );
    }

    /**
     * @dataProvider testImageDataProvider
     */
    public function testImages($html, $expected, $expectedNoImage)
    {
        $html2text = new Html2Text($html);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);

        // Test with ['do_images' => false]
        $html2text = new Html2Text($html, ['do_images' => false]);
        $output = $html2text->getText();

        $this->assertEquals($expectedNoImage, $output);
    }
}
