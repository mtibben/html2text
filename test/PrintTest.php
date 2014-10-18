<?php

namespace Html2Text;

class PrintTest extends \PHPUnit_Framework_TestCase
{
	const HTML = 'Hello, &quot;<b>world</b>&quot;';
	const EXPECTED = 'Hello, "WORLD"';

	public function setUp() {
        $this->html = new Html2Text(self::HTML);
        $this->expectOutputString(self::EXPECTED);		
	}

    public function testP()
    {
        $this->html->p();
    }

    public function testPrint()
    {
        $this->html->printText();
    }

    public function testPrint_text()
    {
        $this->html->print_text();
    }
}
