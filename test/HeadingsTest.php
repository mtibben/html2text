<?php
namespace Html2Text;

/**
 * @copyright ResearchGate GmbH
 */
class HeadingsTest extends \PHPUnit_Framework_TestCase {
    
    public function testHeadings()
    {
        $html =<<<EOT
<h1>Will be UTF-8 (äöüèéилčλ) lowercased</h1>
<h2>Will be UTF-8 (äöüèéилčλ) ucfirst</h2>
<h3>Will be UTF-8 (äöüèéилčλ) titled</h3>
<h5>Will be UTF-8 (äöüèéилčλ) uppercased as default</h5>
<h6>Will be UTF-8 (äöüèéилčλ) uppercased</h6>
<p>Will remain lowercased</p>
EOT;
        $expected =<<<EOT
will be utf-8 (äöüèéилčλ) lowercased

Will be utf-8 (äöüèéилčλ) ucfirst

Will Be Utf-8 (Äöüèéилčλ) Titled

WILL BE UTF-8 (ÄÖÜÈÉИЛČΛ) UPPERCASED AS DEFAULT

WILL BE UTF-8 (ÄÖÜÈÉИЛČΛ) UPPERCASED

Will remain lowercased

EOT;

        $html2text = new Html2Text($html, [
            'elements' => [
                'h1' => ['case' => Html2Text::OPTION_LOWERCASE],
                'h2' => ['case' => Html2Text::OPTION_UCFIRST],
                'h3' => ['case' => Html2Text::OPTION_TITLE],
                'h6' => ['case' => Html2Text::OPTION_UPPERCASE],
            ]
        ]);
        $output = $html2text->getText();

        $this->assertEquals($expected, $output);
    }
}
