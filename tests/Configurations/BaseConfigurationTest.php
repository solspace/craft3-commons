<?php
/**
 * Created by PhpStorm.
 * User: gustavs
 * Date: 26/02/2018
 * Time: 12:38
 */

namespace Configurations;

use PHPUnit\Framework\TestCase;
use Solspace\Commons\Configurations\BaseConfiguration;

class BaseConfigurationTest extends TestCase
{
    /**
     * @expectedException Solspace\Commons\Exceptions\Configurations\ConfigurationException
     * @expectedExceptionMessage Configuration property "random" does not exist. Available properties are: "int, string, array, bool"
     */
    public function testForInexistingProperties()
    {
        new TestConfig(['random' => 'value']);
    }

    public function testConfigHash()
    {
        $configA = new TestConfig(['int' => 2]);
        $configA2 = new TestConfig(['int' => 2]);
        $configB = new TestConfig(['string' => 'string']);
        $configC = new TestConfig();
        $configD = new CloneConfig();

        $this->assertSame(40, \strlen((string) $configA));
        $this->assertSame('0c91ad2cd739fcaf93ef86f7b93fe385cf89c509', $configA->getConfigHash());
        $this->assertSame((string) $configA, (string) $configA2);
        $this->assertNotSame($configA->getConfigHash(), $configB->getConfigHash());
        $this->assertNotSame((string) $configB, (string) $configC);
        $this->assertNotSame((string) $configC, (string) $configD);
    }

    public function getIntDataProvider(): array
    {
        return [
            [51, 51],
            ['32', 32],
            [123.44, 123],
            [123.99, 123],
        ];
    }

    public function testGetNullValues()
    {
        $conf = new TestConfig();
        $this->assertNull($conf->getInt());
        $this->assertNull($conf->getString());
        $this->assertNull($conf->getArray());
        $this->assertNull($conf->getBool());
    }

    /**
     * @dataProvider getIntDataProvider
     *
     * @param mixed $input
     * @param int   $expectedOutput
     */
    public function testGetInt($input, int $expectedOutput)
    {
        $conf = new TestConfig(['int' => $input]);
        $this->assertSame($expectedOutput, $conf->getInt());
    }

    public function testGetString()
    {
        $conf = new TestConfig(['string' => 'a string']);
        $this->assertSame('a string', $conf->getString());
    }

    public function arrayDataProvider(): array
    {
        return [
            [['test', 123], ['test', 123]],
            ['', []],
            [[], []],
            [0, [0]],
        ];
    }

    /**
     * @dataProvider arrayDataProvider
     *
     * @param mixed $input
     * @param array $expectedOutput
     */
    public function testGetArray($input, array $expectedOutput)
    {
        $conf = new TestConfig(['array' => $input]);
        $this->assertSame($expectedOutput, $conf->getArray());
    }

    public function boolDataProvider(): array
    {
        return [
            [false, false],
            [true, true],
            [1, true],
            [0, false],
            ['', false],
            ['asdf', true],
        ];
    }

    /**
     * @dataProvider boolDataProvider
     *
     * @param mixed $input
     * @param bool  $expectedOutput
     */
    public function testGetBool($input, bool $expectedOutput)
    {
        $conf = new TestConfig(['bool' => $input]);
        $this->assertSame($expectedOutput, $conf->getBool());
    }
}

class TestConfig extends BaseConfiguration
{
    protected $int;
    protected $string;
    protected $array;
    protected $bool;

    /**
     * @return mixed
     */
    public function getInt()
    {
        return $this->castToInt($this->int);
    }

    /**
     * @return mixed
     */
    public function getString()
    {
        return $this->castToString($this->string);
    }

    /**
     * @return mixed
     */
    public function getArray()
    {
        return $this->castToArray($this->array);
    }

    /**
     * @return mixed
     */
    public function getBool()
    {
        return $this->castToBool($this->bool);
    }
}

class CloneConfig extends BaseConfiguration
{
    protected $int;
    protected $string;
    protected $array;
    protected $bool;
}
