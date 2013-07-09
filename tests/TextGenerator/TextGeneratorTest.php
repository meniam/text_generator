<?php

namespace TextGeneratorTest;

require_once __DIR__ . '/TestCase.php';

use TextGenerator\TextGenerator;

class TextGeneratorTest extends TestCase
{
    public function testFactory()
    {
        $str = " {hi} ";
        $this->assertInstanceOf('TextGenerator\\XorPart', TextGenerator::factory($str));

        $str = " [hi] ";
        $this->assertInstanceOf('TextGenerator\\OrPart', TextGenerator::factory($str));

        $str = " hi ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));
    }

    public function testRandomGenerate()
    {

        $str = "Hi {men|girl|kid|guy|dude} you are so [+ and +biutifull|amazin|good|{awesome|nerdy :)}|practice]";

        $this->assertNotEquals(TextGenerator::factory($str)->generate(true), TextGenerator::factory($str)->generate(true));
    }
}