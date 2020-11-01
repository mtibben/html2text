<?php

namespace Html2Text;

use PHPUnit\Framework\TestCase;

class PrintTest extends TestCase
{
	const TEST_HTML = 'Hello, &quot;<b>world</b>&quot;';
	const EXPECTED = 'Hello, "WORLD"';

    public function testP()
    {
        $this->html = new Html2Text(self::TEST_HTML);
        $this->html->p();
        $this->expectOutputString(self::EXPECTED);
    }

    public function testPrint_text()
    {
        $this->html = new Html2Text(self::TEST_HTML);
        $this->html->print_text();
        $this->expectOutputString(self::EXPECTED);
    }
}
