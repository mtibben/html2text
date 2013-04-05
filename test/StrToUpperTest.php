<?php

require_once __DIR__.'/../lib/Html2Text/Html2Text.php';

class StrToUpperTest extends PHPUnit_Framework_TestCase
{
    public $input =<<<EOT
<h1>Will be UTF-8 (äöüèéилčλ) uppercased</h1>
<p>Will remain lowercased</p>
EOT;

    public function testStrToUpper()
    {
        $expected_output =<<<EOT
WILL BE UTF-8 (ÄÖÜÈÉИЛČΛ) UPPERCASED

Will remain lowercased
EOT;

        $html2text = new \Html2Text\Html2Text($this->input);
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }
}
