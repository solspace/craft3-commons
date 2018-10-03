<?php

namespace Solspace\Commons\Loggers\Parsers;

class LineParser implements LogParserInterface
{
    private $pattern = '/\[(?P<date>.*)\] (?P<logger>[\w _\-]+).(?P<level>\w+): (?P<message>.*+)/';

    /**
     * LineParser constructor.
     *
     * @param string|null $pattern
     */
    public function __construct(string $pattern = null)
    {
        $this->pattern = $pattern ?: $this->pattern;
    }

    /**
     * @param string $log
     *
     * @return LogLine|null
     */
    public function parse(string $log)
    {
        preg_match($this->pattern, $log, $data);

        if (!isset($data['date'])) {
            return null;
        }

        return new LogLine($data);
    }
}
