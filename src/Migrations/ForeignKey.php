<?php

namespace Solspace\Commons\Migrations;

use Solspace\Commons\Exceptions\Database\DatabaseException;

class ForeignKey
{
    const CASCADE = 'CASCADE';
    const UPDATE = 'UPDATE';
    const RESTRICT = 'RESTRICT';
    const SET_NULL = 'SET NULL';
    const SET_DEFAULT = 'SET DEFAULT';

    /** @var array */
    private static $handlers = [
        self::CASCADE,
        self::UPDATE,
        self::RESTRICT,
        self::SET_NULL,
        self::SET_DEFAULT,
    ];

    /** @var Table */
    private $table;

    /** @var string */
    private $column;

    /** @var string */
    private $referenceTable;

    /** @var string */
    private $referenceColumn;

    /** @var string|null */
    private $onDelete;

    /** @var string|null */
    private $onUpdate;

    /**
     * @param string $handler
     *
     * @return string|null
     *
     * @throws DatabaseException
     */
    private static function getHandler(string $handler = null)
    {
        if (null === $handler) {
            return null;
        }

        if (!\in_array($handler, self::$handlers, true)) {
            $message = sprintf(
                'Cannot set "%s" as onDelete or onUpdate. Use one of these instead: "%s"',
                $handler,
                implode('", "', self::$handlers)
            );

            throw new DatabaseException($message);
        }

        return $handler;
    }

    /**
     * ForeignKey constructor.
     *
     * @throws DatabaseException
     */
    public function __construct(
        Table $table,
        string $column,
        string $referenceTable,
        string $referenceColumn,
        string $onDelete = null,
        string $onUpdate = null
    ) {
        $this->table = $table;
        $this->column = $column;
        $this->referenceTable = $referenceTable;
        $this->referenceColumn = $referenceColumn;
        $this->onDelete = self::getHandler($onDelete);
        $this->onUpdate = self::getHandler($onUpdate);
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->table.'_'.$this->column.'_fk';
    }

    /**
     * @return string[]
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    public function getReferenceTable(): string
    {
        return $this->referenceTable;
    }

    public function getDatabaseReferenceTableName(): string
    {
        return '{{%'.$this->referenceTable.'}}';
    }

    /**
     * @return string[]
     */
    public function getReferenceColumn(): string
    {
        return $this->referenceColumn;
    }

    /**
     * @return string|null
     */
    public function getOnDelete()
    {
        return $this->onDelete;
    }

    /**
     * @return string|null
     */
    public function getOnUpdate()
    {
        return $this->onUpdate;
    }
}
