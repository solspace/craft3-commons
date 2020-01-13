<?php

namespace Solspace\Commons\Loggers\Parsers;

class LogLine
{
    /** @var \DateTime */
    private $date;

    /** @var string */
    private $logger;

    /** @var string */
    private $level;

    /** @var string */
    private $message;

    /** @var mixed */
    private $context;

    /** @var mixed */
    private $extra;

    /**
     * LogLine constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->date    = new \DateTime($data['date']);
        $this->logger  = $data['logger'];
        $this->level   = $data['level'];
        $this->message = $data['message'];
        // $this->context = json_decode($data['context'], true);
        // $this->extra   = json_decode($data['extra'], true);
    }

    /**
     * @return \DateTime|bool
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getLogger(): string
    {
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
