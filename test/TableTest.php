<?php

namespace Html2Text;

require_once("C:\\Stash\\Html2TexT_PR\\html2text\\src\\Html2Text.php");

class TableTest extends \PHPUnit_Framework_TestCase
{
    public function testTable()
    {
        $html =<<<'EOT'
<table>
  <tr>
    <th>Heading 1</th>
    <td>Data 1</td>
  </tr>
  <tr>
    <th>Heading 2</th>
    <td>Data 2</td>
  </tr>
</table>
EOT;

        $expected =<<<'EOT'
 		HEADING 1
 		Data 1

 		HEADING 2
 		Data 2


EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }

    public function testTableWithSpaces()
    {
        $html =<<<'EOT'
<table>
  <tr>
    <th>Heading 1</th>
    <td>Data 1a</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>Data 1b</td>
  </tr>
  <tr>
    <th>Heading 2</th>
    <td>Data 2a</td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>Data 2b</td>
  </tr>
</table>
EOT;

        $expected =<<<'EOT'
 		HEADING 1
 		Data 1a
 		Data 1b

 		HEADING 2
 		Data 2a
 		Data 2b


EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }
}
