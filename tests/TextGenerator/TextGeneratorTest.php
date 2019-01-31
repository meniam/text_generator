<?php

namespace TextGeneratorTest;

use PHPUnit\Framework\TestCase;
use TextGenerator\Part;
use TextGenerator\TextGenerator;

class TextGeneratorTest extends TestCase
{
    public function testFactory()
    {
        $str = " {hi} ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));

        $str = " [hi] ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));

        $str = " hi ";
        $this->assertInstanceOf('TextGenerator\\Part', TextGenerator::factory($str));
    }

    public function testRandomGenerate()
    {
        $str = "Hi {men|girl|kid|guy|dude} you are so [+ and +biutifull|amazin|good|{awesome|nerdy :)}|practice]";
        $this->assertNotEquals(TextGenerator::factory($str, [Part::OPTION_GENERATE_RANDOM => true])->generateRandom(), TextGenerator::factory($str, [Part::OPTION_GENERATE_RANDOM => true])->generateRandom());
    }

    public function testHashedGenerator()
    {
        $str = "Hi {men|girl|kid|guy|dude} you are so [+ and +biutifull|amazin|good|{awesome|nerdy :)}|practice]";

        $this->assertEquals(TextGenerator::factory($str, [Part::OPTION_GENERATE_HASH => 2])->generate(),
                               TextGenerator::factory($str, [Part::OPTION_GENERATE_HASH => "2"])->generate());
    }

    public function testSomeCase()
    {
        $str = "[+, +США|Англии|Китая]";
        $this->assertEquals('США, Англии, Китая', TextGenerator::factory($str)->generateRandom());
    }

    public function testReplace()
    {
        $str = "Hi {%gender%} you are so [+ and +%type%]";

        TextGenerator::addReplaceList(['%gender' => 'female', 'type%' => 'cute']);
        $this->assertEquals("Hi female you are so cute", TextGenerator::factory($str)->generate());
    }
}