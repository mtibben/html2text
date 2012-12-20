<?php

require_once __DIR__.'/../lib/Html2Text/Html2Text.php';

class LinkTest extends PHPUnit_Framework_TestCase
{
    public $input =<<< EOT
<a href="http://example.com">Link text</a>
EOT;


    public function testDoLinksAfter()
    {
        $expected_output =<<<EOT
Link text [1]

Links:
------
[1] http://example.com

EOT;

        $html2text = new \Html2Text\Html2Text($this->input, false, array('do_links' => 'table'));
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }


    public function testDoLinksInline()
    {
        $expected_output =<<<EOT
Link text [http://example.com]
EOT;

        $html2text = new \Html2Text\Html2Text($this->input, false, array('do_links' => 'inline'));
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }


    public function testDoLinksNone()
    {
        $expected_output =<<<EOT
Link text
EOT;

        $html2text = new \Html2Text\Html2Text($this->input, false, array('do_links' => 'none'));
        $output = $html2text->get_text();

        $this->assertEquals($output, $expected_output);
    }

    public function testDoLinksNextline()
    {
        $expected_output =<<<EOT
Link text
[http://example.com]
EOT;

        $html2text = new \Html2Text\Html2Text($this->input, false, array('do_links' => 'nextline'));
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }
}
