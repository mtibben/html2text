<?php

namespace Html2Text;

use PHPUnit\Framework\TestCase;

class MultilineFormattingTest extends TestCase
{
    public function multilineFormattingProvider()
    {
        return array(
            'strong with br' => array(
                '<strong>First<br>Second</strong>',
                "FIRST\nSECOND",
            ),
            'strong with self-closing br' => array(
                '<strong>This would<br />not be converted.</strong><strong>But this would, though</strong>',
                "THIS WOULD\nNOT BE CONVERTED.BUT THIS WOULD, THOUGH",
            ),
            'strong with paragraphs' => array(
                '<strong><p>First</p><p>Second</p></strong>',
                "FIRST\n\nSECOND\n",
            ),
            'b with br' => array(
                '<b>First<br>Second</b>',
                "FIRST\nSECOND",
            ),
            'del with br' => array(
                '<del>First<br>Second</del>',
                "F̶i̶r̶s̶t̶\nS̶e̶c̶o̶n̶d̶",
            ),
            'th with br' => array(
                '<table><tr><th>First<br>Second</th></tr></table>',
                "\t\tFIRST\nSECOND\n\n",
            ),
            'link with br' => array(
                '<a href="http://example.com">First<br>Second</a>',
                "First\nSecond [http://example.com]",
            ),
        );
    }

    /**
     * @dataProvider multilineFormattingProvider
     */
    public function testFormattingSpansLineBreaks($html, $expected)
    {
        $html2text = new Html2Text($html);

        $this->assertEquals($expected, $html2text->getText());
    }
}
