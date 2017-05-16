<?php

namespace Html2Text;

class DisabledNewlineTest extends \PHPUnit_Framework_TestCase
{
    public function testNewLines()
    {
    	$html =<<<EOT
<p>Between this and</p>
<p>this paragraph there should be only one newline</p>

<h1>and this also goes for headings</h1>
EOT;
        $expected =<<<EOT
Between this and
this paragraph there should be only one newline
AND THIS ALSO GOES FOR HEADINGS


EOT;

        $html2text = new Html2Text($html, ['disable_newlines_prepend' => true]);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }
}
