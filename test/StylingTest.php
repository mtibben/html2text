<?php
namespace Html2Text;

/**
 * @copyright ResearchGate GmbH
 */
class StylingTest extends \PHPUnit_Framework_TestCase {

    public function testBold()
    {
        $html =<<<EOT

<div>The following <strong>will be UTF-8 (äöüèéилčλ) uppercased</strong> when parsed</div>
<div>The following <b>will be UTF-8 (äöüèéилčλ) lowercased</b> when parsed</div>
<p>The following  will remain lowercased when parsed</p>
EOT;

        $expected =<<<EOT
The following WILL BE UTF-8 (ÄÖÜÈÉИЛČΛ) UPPERCASED when parsed
The following will be utf-8 (äöüèéилčλ) lowercased when parsed
The following will remain lowercased when parsed

EOT;

        $html2text = new Html2Text($html, [
            'width' => 0,
            'elements' => [
                'strong' => ['case' => Html2Text::OPTION_UPPERCASE],
                'b' => ['case' => Html2Text::OPTION_LOWERCASE],
            ]
        ]);

        $this->assertEquals($expected, $html2text->getText());
    }

    public function testItalic() {
        $html =<<<EOT
<i>This is italic</i>
EOT;

        $expected =<<<EOT
_This is italic_
EOT;

        $html2text = new Html2Text($html);

        $this->assertEquals($expected, $html2text->getText());
    }
}
