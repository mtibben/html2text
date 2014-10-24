<?php

namespace Html2Text;

class BlockquoteTest extends \PHPUnit_Framework_TestCase
{
    public function testBlockquote()
    {
        $html =<<<'EOT'
<p>Before</p>
<blockquote>

Foo bar baz


HTML symbols &amp;

</blockquote>
<p>After</p>
EOT;

        $expected =<<<'EOT'
Before 

> Foo bar baz HTML symbols &

After
EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }
}
