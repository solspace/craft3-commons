<?php

namespace Solspace\Commons\Helpers;

use PHPUnit\Framework\TestCase;

class ComparisonHelperTest extends TestCase
{
    /**
     * @return array
     */
    public function textDataProvider(): array
    {
        return [
            ['(so$^me@*.com', '(so$^me@gmail.com', false],
            ['some*string', 'some  string', true],
            ['viagr*', 'viagra very more text', true],
            ['viagr*', 'some viagra1! text', true],
            ['viagr*', 'shviagra1!', false],
            ['viagr*', 'this long string contains viagra1! in it', true],
            ['viagr*', 'sviagra', false],
            ['vi*ra', 'viagra', true],
            ['vi*ra', 'viagarana ra', true],
            ['some@*.com', 'some@gmail.com', true],
            ['some@*.com', 'some@hotmail.com', true],
            ['some@*.com', 'some@gmail.ru', false],
            ['[some@*.com', '[some@gmail.com', false],
            ['[some@*.com', 'some@gmail.com', false],
            ['"Beautiful girls"', 'Beautiful', true],
            ['"Beautiful girls"', 'girls', true],
            ['"Beautiful girls"', 'Beautiful girls', true],
            ['*@mail.me', 'some@mail.me', true],
            ['some@*.me', 'some@mail.me', true],
            ['some@mail.*', 'some@mail.me', true],
            ['+974', '974', true],
            ['+974', '+974', true],
            ['b*brides', 'bestbrides', true],
            ['*charming*', 'charmingdate', true],
        ];
    }

    /**
     * @param string $pattern
     * @param string $string
     * @param bool   $expectedResult
     *
     * @dataProvider textDataProvider
     */
    public function testTextMatchesWildcardPattern(string $pattern, string $string, bool $expectedResult)
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

    /**
     * @return array
     */
    public function wordDataProvider(): array
    {
        return [
            ['(so$^me@*.com', '(so$^me@gmail.com', true],
            ['some*string', 'some  string', true],
            ['viagr*', 'viagra very more text', true],
            ['viagr*', 'viagra1! text', true],
            ['viagr*', 'shviagra1!', false],
            ['viagr*', 'this long string contains viagra1! in it', false],
            ['viagr*', 'sviagra', false],
            ['vi*ra', 'viagra', true],
            ['vi*ra', 'viagarana ra', true],
            ['some@*.com', 'some@gmail.com', true],
            ['some@*.com', 'some@hotmail.com', true],
            ['some@*.com', 'some@gmail.ru', false],
            ['[some@*.com', '[some@gmail.com', true],
            ['[some@*.com', 'some@gmail.com', false],
        ];
    }

    /**
     * @param string $pattern
     * @param string $string
     * @param bool   $expectedResult
     *
     * @dataProvider wordDataProvider
     */
    public function testWordMatchesWildcardPattern(string $pattern, string $string, bool $expectedResult)
    {
        $result = ComparisonHelper::stringMatchesWildcard($pattern, $string);

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
