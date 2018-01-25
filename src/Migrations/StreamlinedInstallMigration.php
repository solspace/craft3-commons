<?php

namespace Solspace\Commons\Migrations;

use craft\db\Migration;

abstract class StreamlinedInstallMigration extends Migration
{
    /**
     * @inheritdoc
     */
    final public function safeUp()
    {
        foreach ($this->defineTableData() as $table) {
            $table->addField('dateCreated', $this->dateTime()->notNull()->defaultExpression('NOW()'));
            $table->addField('dateUpdated', $this->dateTime()->notNull()->defaultExpression('NOW()'));
            $table->addField('uid', $this->char(36)->defaultValue(0));

            $this->createTable($table->getName(), $table->getFieldArray(), $table->getOptions());

            foreach ($table->getIndexes() as $index) {
                $this->createIndex(
                    $index->getName(),
                    $table->getName(),
                    $index->getColumns(),
                    $index->isUnique()
                );
            }
        }

        foreach ($this->defineTableData() as $table) {
            foreach ($table->getForeignKeys() as $foreignKey) {
                $this->addForeignKey(
                    $foreignKey->getName(),
                    $table->getName(),
                    $foreignKey->getColumn(),
                    $foreignKey->getReferenceTable(),
                    $foreignKey->getReferenceColumn(),
                    $foreignKey->getOnDelete(),
                    $foreignKey->getOnUpdate()
                );
            }
        }
    }

    /**
     * @inheritdoc
     */
    final public function safeDown()
    {
        foreach ($this->defineTableData() as $table) {
            foreach ($table->getForeignKeys() as $foreignKey) {
                $this->dropForeignKey($foreignKey->getName(), $table->getName());
            }
        }

        foreach ($this->defineTableData() as $table) {
            $this->dropTableIfExists($table->getName());
        }
    }

    /**
     * @return Table[]
     */
    abstract protected function defineTableData(): array;
}
