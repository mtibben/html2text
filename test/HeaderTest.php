<?php

namespace Html2Text;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testToUpper()
    {
    	$html =<<<EOT
<h1>Will be UTF-8 (äöüèéилčλ) uppercased</h1>
<p>Will remain lowercased</p>
EOT;
        $expected =<<<EOT
WILL BE UTF-8 (ÄÖÜÈÉИЛČΛ) UPPERCASED

Will remain lowercased

EOT;

        $html2text = new Html2Text($html);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }

    public function testDIfferentHeadingOptions ()
    {
      $html =<<<EOT
<h1>Will be UTF-8 (äöüèéилčλ) lowercased</h1>
<h4>Will be UTF-8 (äöüèéилčλ) lowercased with colon</h4>
<h5>Will be UTF-8 (äöüèéилčλ) uppercased as default</h5>
<h6>Will be UTF-8 (äöüèéилčλ) uppercased</h6>
<p>Will remain lowercased</p>
EOT;
        $expected =<<<EOT
Will be utf-8 (äöüèéилčλ) lowercased

Will be utf-8 (äöüèéилčλ) lowercased with colon:

WILL BE UTF-8 (ÄÖÜÈÉИЛČΛ) UPPERCASED AS DEFAULT

WILL BE UTF-8 (ÄÖÜÈÉИЛČΛ) UPPERCASED

Will remain lowercased

EOT;

        $html2text = new Html2Text($html, [
          'heading' => [
            '1' => ['case' => Html2Text::OPTION_LOWERCASE],
            '4' => ['case' => Html2Text::OPTION_LOWERCASE, 'colon' => true],
            '6' => ['case' => Html2Text::OPTION_UPPERCASE],
          ]
        ]);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }
}
