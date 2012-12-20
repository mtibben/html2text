<?php

require_once __DIR__.'/../lib/Html2Text/Html2Text.php';

class LinkOverrideTest extends PHPUnit_Framework_TestCase
{
    public $input =<<< EOT
<a href="http://example.com" class="_html2text_link_nextline">Link text</a>
EOT;


    public function testDoLinksNextline()
    {
        $expected_output =<<<EOT
Link text
[http://example.com]
EOT;

        $html2text = new \Html2Text\Html2Text($this->input, false, array('do_links' => 'inline'));
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }
}
