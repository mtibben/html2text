<?php

namespace Html2Text;

class PreTest extends \PHPUnit_Framework_TestCase
{
    public function testPre()
    {
        $html =<<<'EOT'
<p>Before</p>
<pre>

Foo bar baz


HTML symbols &amp;

</pre>
<p>After</p>
EOT;

        $expected =<<<'EOT'
Before 

Foo bar baz

HTML symbols &

After
EOT;

        $html2text = new Html2Text($html);
        $this->assertEquals($expected, $html2text->getText());
    }
}
