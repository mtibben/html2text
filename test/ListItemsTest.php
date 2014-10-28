<?php

require_once __DIR__.'/../lib/Html2Text/Html2Text.php';

class ListItemsTest extends PHPUnit_Framework_TestCase
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
        $expected_output =<<<EOT
 	* one
 	* two
 	* 


EOT;

        $html2text = new \Html2Text\Html2Text($input);
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
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
        $expected_output =<<<EOT
 	1. one
 	2. two
 	3. 


EOT;

        $html2text = new \Html2Text\Html2Text($input);
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }

    public function testLargeOrderedList()
    {
        $input = <<<EOT
<ol>
    <li>one</li>
    <li>two</li>
    <li>three</li>
    <li>four</li>
    <li>five</li>
    <li>six</li>
    <li>seven</li>
    <li>eight</li>
    <li>nine</li>
    <li>ten</li>
    <li>eleven</li>
</ol>
EOT;
        $expected_output =<<<EOT
 	 1. one
 	 2. two
 	 3. three
 	 4. four
 	 5. five
 	 6. six
 	 7. seven
 	 8. eight
 	 9. nine
 	10. ten
 	11. eleven


EOT;

        $html2text = new \Html2Text\Html2Text($input);
        $output = $html2text->get_text();

        $this->assertEquals($expected_output, $output);
    }
}
