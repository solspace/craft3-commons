<?php

namespace Loggers;

use PHPUnit\Framework\TestCase;
use Solspace\Commons\Loggers\Parsers\LogLine;
use Solspace\Commons\Loggers\Readers\LineLogReader;

class LineLogReaderTest extends TestCase
{
    private $logPath = __DIR__ . '/test.log';

    /** @var LineLogReader */
    private $logReader;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->logReader = new LineLogReader($this->logPath);
    }

    public function testEmptyOnInexistingFile()
    {
        $logReader = new LineLogReader('/completely/random/fake/path43214321.log');

        $this->assertCount(0, $logReader);
        $this->assertCount(0, $logReader->getLastLines(20));
    }

    public function testReadsCorrectLines()
    {
        $this->assertSame(5, $this->logReader->count());
    }

    public function testReadsLastThreeLines()
    {
        $this->assertCount(3, $this->logReader->getLastLines(3));
    }

    public function testAskingMoreLinesReturnsMaximum()
    {
        $this->assertCount(5, $this->logReader->getLastLines(20));
    }

    public function testDefaultParser()
    {
        list($lineA, $lineB, $lineC, $lineD) = $this->logReader->getLastLines(4);
        $mockA = new LogLine([
            'date'    => '2018-10-04 05:22:11',
            'logger'  => 'Third',
            'level'   => 'INFO',
            'message' => 'An error message went there [] []',
        ]);

        $mockB = new LogLine([
            'date'    => '2018-08-14 11:05:59',
            'logger'  => 'Third',
            'level'   => 'NOTICE',
            'message' => 'An error message went here [] []',
        ]);

        $mockC = new LogLine([
            'date'    => '2018-07-12 18:34:01',
            'logger'  => 'Other Category',
            'level'   => 'CRITICAL',
            'message' => 'Some error message [] []',
        ]);

        $mockD = new LogLine([
            'date'    => '2018-02-22 22:42:32',
            'logger'  => 'Some Category',
            'level'   => 'ERROR',
            'message' => 'There has been an error {"context": "value"} []',
        ]);

        $this->assertEquals($mockA, $lineA);
        $this->assertEquals($mockB, $lineB);
        $this->assertEquals($mockC, $lineC);
        $this->assertEquals($mockD, $lineD);
    }
}
