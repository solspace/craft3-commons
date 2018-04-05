<?php

namespace Solspace\Commons\Helpers;


use PHPUnit\Framework\TestCase;

class ComparisonHelperTest extends TestCase
{
    /**
     * @return array
     */
    public function wildcardPatternDataProvider(): array
    {
        return [
            ['some*string', 'some  string', true],
            ['viagr*', 'viagra', true],
            ['viagr*', 'viagra1!', true],
            ['viagr*', 'shviagra1!', false],
            ['viagr*', 'this long string contains viagra1! in it', true],
            ['viagr*', 'sviagra', false],
            ['vi*ra', 'viagra', true],
            ['vi*ra', 'viagarana ra', true],
            ['some@*.com', 'some@gmail.com', true],
            ['some@*.com', 'some@hotmail.com', true],
            ['some@*.com', 'some@gmail.ru', false],
        ];
    }

    /**
     * @param string $pattern
     * @param string $string
     * @param bool   $expectedResult
     *
     * @dataProvider wildcardPatternDataProvider
     */
    public function testStringMatchesWildcardPattern(string $pattern, string $string, bool $expectedResult)
    {
        $result = ComparisonHelper::stringContainsWildcardKeyword($pattern, $string);

        $this->assertSame(
            $expectedResult,
            $result,
            sprintf(
                'Pattern "%s" returns "%s" for "%s". Expected: "%s"',
                $pattern,
                $result ? 'true' : 'false',
                $string,
                $expectedResult ? 'true' : 'false'
            )
        );
    }
}
