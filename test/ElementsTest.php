<?php

namespace Html2Text;

class ElementsTest extends \PHPUnit_Framework_TestCase
{

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
