<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\XorPart;

class XorPartTest extends TestCase
{
    public function testGetRandomTemplate()
    {
        $str = "1|2|3|4|5|6";
        $part = new XorPart($str);
        $this->assertNotEquals($part->generate(true), $part->generate(true));

        $part = new XorPart($str);
        for ($i=1; $i<= count(explode('|', $str)); $i++) {
            $this->assertEquals($i, $part->generate());
        }
        $this->assertEquals('1', $part->generate());
    }
}