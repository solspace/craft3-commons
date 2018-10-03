<?php

namespace Solspace\Commons\Loggers\Parsers;

interface LogParserInterface
{
    /**
     * @param string $log
     *
     * @return mixed
     */
    public function parse(string $log);
}
