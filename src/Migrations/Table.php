<?php

namespace Solspace\Commons\Migrations;

use yii\db\ColumnSchemaBuilder;

class Table
{
    /** @var string */
    private $name;

    /** @var string */
    private $options;

    /** @var Field[] */
    private $fields;

    /** @var Index[] */
    private $indexes;

    /** @var ForeignKey[] */
    private $foreignKeys;

    /**
     * Table constructor.
     *
     * @param string      $name
     * @param string|null $options
     */
    public function __construct(
        string $name,
        string $options = null
    ) {
        $this->name           = $name;
        $this->options        = $options;
        $this->fields         = [];
        $this->indexes        = [];
        $this->foreignKeys    = [];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getDatabaseName(): string
    {
        return '{{%' . $this->getName() . '}}';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string              $name
     * @param ColumnSchemaBuilder $definition
     *
     * @return Table
     */
    public function addField(string $name, ColumnSchemaBuilder $definition): Table
    {
        $this->fields[] = new Field($name, $definition);

        return $this;
    }

    /**
     * @param array       $columns
     * @param bool        $unique
     * @param string|null $prefix
     *
     * @return Table
     */
    public function addIndex(array $columns, bool $unique = false, string $prefix = null): Table
    {
        $this->indexes[] = new Index($columns, $unique, $prefix);

        return $this;
    }

    /**
     * @param string      $column
     * @param string      $refTable
     * @param string      $refColumn
     * @param string|null $onDelete
     * @param string|null $onUpdate
     *
     * @return Table
     */
    public function addForeignKey(
        string $column,
        string $refTable,
        string $refColumn,
        string $onDelete = null,
        string $onUpdate = null
    ): Table {
        $this->foreignKeys[] = new ForeignKey(
            $this,
            $column,
            $refTable,
            $refColumn,
            $onDelete,
            $onUpdate
        );

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getFieldArray(): array
    {
        $data = [];

        foreach ($this->fields as $field) {
            $data[$field->getName()] = $field->getDefinition();
        }

        return $data;
    }

    /**
     * @return Index[]
     */
    public function getIndexes(): array
    {
        return $this->indexes;
    }

    /**
     * @return ForeignKey[]
     */
    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }
}
