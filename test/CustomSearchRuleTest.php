<?php

require_once __DIR__.'/../lib/Html2Text/Html2Text.php';

class CustomSearchRuleTest extends PHPUnit_Framework_TestCase
{
    public $input =<<<EOT
This library name is &laquo;Html2Text&raquo;
EOT;

    public function testStrToUpper()
    {
        $expected_output =<<<EOT
This library name is «Html2Text»
EOT;

        $html2text = new \Html2Text\Html2Text($this->input);
        $html2text->add_ent_search_and_replace(array(
            '/&laquo;/i' => '«',
            '/&raquo;/i' => '»'
        ));

        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }
}
