<?php

namespace Html2Text;

class ListItemsTest extends \PHPUnit_Framework_TestCase
{

    public function testUnorderedList()
    {
        $input = <<<EOT
<ul>
    <li>one</li>
    <li>two</li>
    <li>
</ul>
EOT;
        $expected =<<<EOT
 	* one
 	* two
 	* 


EOT;

        $html2text = new \Html2Text\Html2Text($input);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }

    public function testOrderedList()
    {
        $input = <<<EOT
<ol>
    <li>one</li>
    <li>two</li>
    <li>
</ol>
EOT;
        $expected =<<<EOT
 	1. one
 	2. two
 	3. 


EOT;

        $html2text = new \Html2Text\Html2Text($input);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }
}
