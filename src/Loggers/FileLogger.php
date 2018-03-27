<?php

namespace Solspace\Commons\Loggers;

use craft\helpers\StringHelper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileLogger
{
    /** @var Logger */
    private $logger;

    /** @var FileLogger */
    private static $instance;

    /**
     * @param string      $category
     * @param string|null $logPath
     *
     * @return FileLogger
     */
    public static function getInstance(string $category, string $logPath = null): FileLogger
    {
        $hash = sha1($category . $logPath);

        if (null === self::$instance) {
            self::$instance = [];
        }

        if (!isset(self::$instance[$hash])) {
            self::$instance[$hash] = new self($category, $logPath);
        }

        return self::$instance[$hash];
    }

    /**
     * CustomFileLogger constructor.
     *
     * @param string      $category
     * @param string|null $logPath - e.g. "/var/logs/{{category}}.log"
     *                             defaults to `CRAFT_STORAGE_PATH . "/logs/{{category}}.log"`
     *
     * @throws \Exception
     */
    private function __construct(string $category, string $logPath = null)
    {
        $category = StringHelper::toKebabCase($category, '_');

        if (null === $logPath) {
            $logPath = \Craft::$app->path->getLogPath() . '/{{category}}.log';
        }

        $logPath = \Craft::$app->view->renderString($logPath, ['category' => $category]);

        $this->logger = new Logger($category);
        $this->logger->pushHandler(
            new StreamHandler(
                $logPath,
                Logger::INFO
            )
        );
    }

    /**
     * @param mixed $level
     * @param mixed $message
     * @param array $context
     *
     * @return bool
     */
    public function log($level, $message, array $context = []): bool
    {
        return $this->logger->log($level, $message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     *
     * @return bool
     */
    public function debug($message, array $context = []): bool
    {
        return $this->logger->debug($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     *
     * @return bool
     */
    public function info($message, array $context = []): bool
    {
        return $this->logger->info($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     *
     * @return bool
     */
    public function warning($message, array $context = []): bool
    {
        return $this->logger->warning($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     *
     * @return bool
     */
    public function error($message, array $context = []): bool
    {
        return $this->logger->error($message, $context);
    }

    /**
     * @param mixed $message
     * @param array $context
     *
     * @return bool
     */
    public function critical($message, array $context = []): bool
    {
        return $this->logger->critical($message, $context);
    }
}