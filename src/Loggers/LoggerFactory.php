<?php

namespace Solspace\Commons\Loggers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    const TYPE_FILE_LOGGER = 'file_logger';

    /** @var FileLogger */
    private static $instance = [];

    /**
     * @param string $category
     * @param string $logfilePath
     *
     * @return LoggerInterface
     */
    public static function getOrCreateFileLogger(string $category, string $logfilePath): LoggerInterface
    {
        $hash = sha1($category . $logfilePath);

        if (!isset(self::$instance[$hash])) {
            $logger = new Logger($category);
            $logger->pushHandler(new StreamHandler($logfilePath, Logger::DEBUG));

            self::$instance[$hash] = $logger;
        }

        return self::$instance[$hash];
    }
}
