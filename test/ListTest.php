<?php

namespace Html2Text;

use PHPUnit\Framework\TestCase;

class ListTest extends TestCase
{
    public function testList()
    {
        $html =<<<'EOT'
<ul>
  <li>Item 1</li>
  <li>Item 2</li>
  <li>Item 3</li>
</ul>
EOT;

        $expected =<<<'EOT'
 	* Item 1
 	* Item 2
 	* Item 3


EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }

    public function testOrderedList()
    {
        $html =<<<'EOT'
<ol>
  <li>Item 1</li>
  <li>Item 2</li>
  <li>Item 3</li>
</ol>
EOT;

        $expected =<<<'EOT'
 	1. Item 1
 	2. Item 2
 	3. Item 3


EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }

    public function testOrderedListWithStart()
    {
        $html = '<ol start="5"><li>Item 5</li><li>Item 6</li></ol>';
        $expected = "\t5. Item 5\n\t6. Item 6";

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, trim($html2text->getText(), "\n"));
    }

    public function testNestedOrderedAndUnorderedLists()
    {
        $html = '<ol><li>One<ul><li>Sub a</li><li>Sub b</li></ul></li><li>Two<ol><li>Sub 1</li></ol></li></ol>';
        $expected = "\t1. One\n\n\t* Sub a\n\t* Sub b\n\n\t2. Two\n\n\t1. Sub 1";

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, trim($html2text->getText(), "\n"));
    }
}
