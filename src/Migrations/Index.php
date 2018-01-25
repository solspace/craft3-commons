<?php

namespace Solspace\Commons\Migrations;

class Index
{
    /** @var string[] */
    private $columns;

    /** @var bool */
    private $unique;

    /**
     * Index constructor.
     *
     * @param string[] $columns
     * @param bool     $unique
     */
    public function __construct(array $columns, bool $unique = false)
    {
        $this->columns = $columns;
        $this->unique  = $unique;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return implode('_', $this->columns) . ($this->unique ? '_unq' : '') . '_idx';
    }

    /**
     * @return string[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }
}
