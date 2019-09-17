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
        $string = "test best\nchest,rest;rest;crest\n\neasiest     schmest|nest\n";

        $this->assertSame(
            ['test', 'best', 'chest', 'rest', 'crest', 'easiest', 'schmest', 'nest'],
            StringHelper::extractSeparatedValues($string)
        );
    }

    public function recursiveImplodeDataProvider()
    {
        return [
            ['|', ['a', 2, 'test'], 'a|2|test'],
            [', ', ['a', 2, ['test', 'asd']], 'a, 2, test, asd'],
            [', ', [['a', 'best'], 2, ['test', 'asd']], 'a, best, 2, test, asd'],
            [',', 'test', 'test'],
        ];
    }

    /**
     * @param string $glue
     * @param array  $input
     * @param string $expectedOutput
     *
     * @dataProvider recursiveImplodeDataProvider
     */
    public function testImplodeRecursively($glue, $input, $expectedOutput)
    {
        $output = StringHelper::implodeRecursively($glue, $input);

        $this->assertSame($expectedOutput, $output);
    }
}
