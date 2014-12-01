<?php

namespace Html2Text;

class BlankSpacesTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test multiple &nbsp; in a row.
     */
    public function testMultipleBlankSpaces()
    {
        $html =<<<'EOT'
Replace double spaces:&nbsp;&nbsp;Continue string afterwards.
EOT;

        $expected =<<<'EOT'
Replace double spaces:	Continue string afterwards.
EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }


    /**
     * Test multiple &emsp; in a row.
     */
    public function testMultipleEmSpaces()
    {
        $html =<<<'EOT'
Replace double spaces:&emsp;&emsp;Continue string afterwards.
EOT;

        $expected =<<<'EOT'
Replace double spaces:	Continue string afterwards.
EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }


    /**
     * Test multiple &ensp; in a row.
     */
    public function testMultipleEnspaces()
    {
        $html =<<<'EOT'
Replace double spaces:&ensp;&ensp;Continue string afterwards.
EOT;

        $expected =<<<'EOT'
Replace double spaces:	Continue string afterwards.
EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }


    /**
     * Test multiple &thinsp; in a row.
     */
    public function testMultipleThinspaces()
    {
        $html =<<<'EOT'
Replace double spaces:&thinsp;&thinsp;Continue string afterwards.
EOT;

        $expected =<<<'EOT'
Replace double spaces:	Continue string afterwards.
EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }


    /**
     * Create a random string containing 2 spaces in a row, followed by one extra space.
     */
    public function testRandomSpaces()
    {
        $possibleSpaces = array("&nbsp;", "&emsp;", "&ensp;", "&thinsp;");
        shuffle($possibleSpaces);
        $randomSpacesString = "Replace double spaces:".$possibleSpaces[0].$possibleSpaces[0].$possibleSpaces[1]."Continue string afterwards.";

        $expected =<<<'EOT'
Replace double spaces:	 Continue string afterwards.
EOT;

        $html2text = new Html2Text($randomSpacesString);
        $this->assertEquals($expected, $html2text->getText());
    }

    /**
     * Create a random string containing 2 spaces in a row, followed by another 2 spaces in a row but of a different html entity.
     */
    public function testRandomSpacesDouble()
    {
        $possibleSpaces = array("&nbsp;", "&emsp;", "&ensp;", "&thinsp;");
        shuffle($possibleSpaces);
        $randomSpacesString = "Replace double spaces:".$possibleSpaces[0].$possibleSpaces[0].$possibleSpaces[1].$possibleSpaces[1]."Continue string afterwards.";

        $expected =<<<'EOT'
Replace double spaces:		Continue string afterwards.
EOT;

        $html2text = new Html2Text($randomSpacesString);
        $this->assertEquals($expected, $html2text->getText());
    }


}
