<?php

namespace Solspace\Commons\Migrations;

class PrimaryKey
{
    /** @var string[] */
    private $columns;

    /**
     * Primary Key constructor.
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function __toString(): string
    {
        return implode('_', $this->columns).'_pk';
    }

    /**
     * @return string[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
