<?php

namespace Solspace\Commons\Migrations;

class Index
{
    /** @var string[] */
    private $columns;

    /** @var bool */
    private $unique;

    /** @var string */
    private $prefix;

    /**
     * Index constructor.
     *
     * @param string[]    $columns
     * @param bool        $unique
     * @param string|null $prefix
     */
    public function __construct(array $columns, bool $unique = false, string $prefix = null)
    {
        $this->columns = $columns;
        $this->unique  = $unique;
        $this->prefix  = $prefix;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return ($this->prefix ?? '') . implode('_', $this->columns) . ($this->unique ? '_unq' : '') . '_idx';
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
