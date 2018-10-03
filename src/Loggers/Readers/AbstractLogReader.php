<?php

namespace Solspace\Commons\Loggers\Readers;

use Solspace\Commons\Loggers\Parsers\LineParser;
use Solspace\Commons\Loggers\Parsers\LogParserInterface;

class AbstractLogReader
{
    /** @var string */
    protected $defaultParserPattern;

    /**
     * AbstractLogReader constructor.
     *
     * @param string $defaultParserPattern
     */
    public function __construct(string $defaultParserPattern = null)
    {
        $this->defaultParserPattern = $defaultParserPattern;
    }

    /**
     * @return LogParserInterface
     */
    protected function getDefaultParser(): LogParserInterface
    {
        return new LineParser($this->defaultParserPattern);
    }
}
