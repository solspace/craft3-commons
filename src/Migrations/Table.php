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

    /** @var PrimaryKey[] */
    private $primaryKeys;

    /**
     * Table constructor.
     */
    public function __construct(
        string $name,
        string $options = null
    ) {
        $this->name = $name;
        $this->options = $options;
        $this->fields = [];
        $this->indexes = [];
        $this->foreignKeys = [];
        $this->primaryKeys = [];
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getDatabaseName(): string
    {
        return '{{%'.$this->getName().'}}';
    }

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

    public function addPrimaryKey(array $columns): self
    {
        $this->primaryKeys[] = new PrimaryKey($columns);

        return $this;
    }

    public function addField(string $name, ColumnSchemaBuilder $definition): self
    {
        $this->fields[] = new Field($name, $definition);

        return $this;
    }

    public function addIndex(array $columns, bool $unique = false, string $prefix = null): self
    {
        $this->indexes[] = new Index($columns, $unique, $prefix);

        return $this;
    }

    public function addForeignKey(
        string $column,
        string $refTable,
        string $refColumn,
        string $onDelete = null,
        string $onUpdate = null
    ): self {
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

    /**
     * @return PrimaryKey[]
     */
    public function getPrimaryKeys(): array
    {
        return $this->primaryKeys;
    }
}
