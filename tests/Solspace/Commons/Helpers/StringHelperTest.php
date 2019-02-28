<?php

namespace Solspace\Commons\Helpers;

use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testBooleanAttribute()
    {
        $result = StringHelper::compileAttributeStringFromArray(['data-test' => true]);
        $this->assertEquals(' data-test ', $result);
    }

    public function testFalseBooleanAttribute()
    {
        $result = StringHelper::compileAttributeStringFromArray(['data-test' => false]);
        $this->assertEquals('', $result);
    }

    public function testNumberAttribute()
    {
        $result = StringHelper::compileAttributeStringFromArray(['number' => 1]);
        $this->assertEquals(' number="1" ', $result);
    }

    public function testStringAttribute()
    {
        $result = StringHelper::compileAttributeStringFromArray(['string' => 'some string']);
        $this->assertEquals(' string="some string" ', $result);
    }

    public function testEmptyAttribute()
    {
        $result = StringHelper::compileAttributeStringFromArray(['empty' => '']);
        $this->assertEquals('', $result);
    }

    public function testNullAttribute()
    {
        $result = StringHelper::compileAttributeStringFromArray(['null' => null]);
        $this->assertEquals('', $result);
    }

    public function testMultipleAttributes()
    {
        $result = StringHelper::compileAttributeStringFromArray(
            [
                'string' => 'some string',
                'number' => 23,
                'data-bool' => true,
                'another_bool' => false,
            ]
        );
        $this->assertEquals(' string="some string" number="23" data-bool ', $result);
    }

    public function testSeparatesItems()
    {
        $string = "test best\nchest,rest;crest\n\neasiest     schmest|nest";

        $this->assertSame(
            ['test', 'best', 'chest', 'rest', 'crest', 'easiest', 'schmest', 'nest'],
            StringHelper::extractSeparatedValues($string)
        );
    }
}
