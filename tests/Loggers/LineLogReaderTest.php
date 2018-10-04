<?php

namespace Loggers;

use PHPUnit\Framework\TestCase;
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
        $this->assertSame(4, $this->logReader->count());
    }

    public function testReadsLastThreeLines()
    {
        $this->assertCount(3, $this->logReader->getLastLines(3));
    }
}
