<?php

namespace Solspace\Commons\Migrations;

use yii\db\ColumnSchemaBuilder;

class Field
{
    /** @var string */
    private $name;

    /** @var ColumnSchemaBuilder */
    private $definition;

    /**
     * Field constructor.
     *
     * @param string              $name
     * @param ColumnSchemaBuilder $definition
     */
    public function __construct(string $name, ColumnSchemaBuilder $definition)
    {
        $this->name          = $name;
        $this->definition    = $definition;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ColumnSchemaBuilder
     */
    public function getDefinition(): ColumnSchemaBuilder
    {
        return $this->definition;
    }
}
