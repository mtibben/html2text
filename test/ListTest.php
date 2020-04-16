<?php

namespace Html2Text;

class ListTest extends \PHPUnit_Framework_TestCase
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

        $expected = "    * Item 1\n    * Item 2\n    * Item 3\n";

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

        $expected = "    * Item 1\n    * Item 2\n    * Item 3\n";

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }

    public function testNestedList()
    {
        $html =<<<'EOT'
<ul>
  <li>Coffee</li>
  <li>Tea
    <ul>
      <li>Black tea</li>
      <li>Green tea</li>
    </ul>
  </li>
  <li>Milk</li>
</ul>
EOT;

        $expected = "    * Coffee\n    * Tea \n    \n        * Black tea\n        * Green tea\n    \n    * Milk\n";

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }
}
