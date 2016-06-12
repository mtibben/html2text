<?php

namespace Html2Text;

class ElementsTest extends \PHPUnit_Framework_TestCase
{
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

    public function testPrependAndAppend() {
      $html =<<<EOT
  <h1>Should have "AAA " prepended</h1>
  <h4>Should have " BBB" appended</h4>
  <h6>Should have "AAA " prepended and " BBB" appended</h6>
  <li>Dash instead of asterisk</li>
EOT;

    $expected = <<<EOT
AAA Should have "AAA " prepended

Should have " BBB" appended BBB

AAA Should have "AAA " prepended and " BBB" appended BBB

	- Dash instead of asterisk

EOT;

    $html2text = new Html2Text($html, [
      'elements' => [
        'h1' => ['case' => Html2Text::OPTION_NONE, 'prepend' => "\nAAA "],
        'h4' => ['case' => Html2Text::OPTION_NONE, 'append' => " BBB\n"],
        'h6' => ['case' => Html2Text::OPTION_NONE, 'prepend' => "\nAAA ", 'append' => " BBB\n"],
        'li' => ['prepend' => "\n\t- "]
      ]
    ]);

    $this->assertEquals($expected, $html2text->getText());
}

  public function testBoldness()
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

    public function testReplace() {
        $html =<<<EOT
  <h1>Should have "AAA" changed to BBB</h1>
   <li>• Custom bullet should be removed</li>
EOT;

        $expected = <<<EOT
Should have "BBB" changed to BBB

 	* Custom bullet should be removed

EOT;

        $html2text = new Html2Text($html, [
            'width' => 0,
            'elements' => [
                'h1' => ['case' => Html2Text::OPTION_NONE, 'replace' => ['AAA', 'BBB']],
                'li' => [ 'replace' => ['•', '']],
            ]
        ]);

        $this->assertEquals($expected, $html2text->getText());
    }
}
