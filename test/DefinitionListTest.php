<?php

require_once __DIR__.'/../lib/Html2Text/Html2Text.php';

class DefinitionListTest extends PHPUnit_Framework_TestCase
{
    public $input =<<< EOT
<dl>
  <dt>Definition Term:</dt>
  <dd>Definition Description<dd>
</dl>
EOT;


    public function testDefinitionList()
    {
        $expected_output =<<<EOT
 	* Definition Term: Definition Description 


EOT;

        $html2text = new \Html2Text\Html2Text($this->input);
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }
}
