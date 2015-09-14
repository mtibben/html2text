<?php

namespace Html2Text;

class SpaceTest extends \PHPUnit_Framework_TestCase
{
    public function testSpaces()
    {
        $html = new Html2Text('This&nbsp;is&nbsp;a&nbsp;text&nbsp;with&nbsp;a&nbsp;lot&nbsp;of&nbsp;spaces.');

        $this->assertEquals('This is a text with a lot of spaces.', $html->getText());
    }
}
