<?php

namespace Solspace\Commons\Migrations;

use craft\db\Migration;

abstract class StreamlinedInstallMigration extends Migration
{
    /**
     * @inheritdoc
     */
    final public function safeUp(): bool
    {
        if (!$this->beforeInstall()) {
            return false;
        }

        foreach ($this->defineTableData() as $table) {
            $table->addField('dateCreated', $this->dateTime()->notNull());
            $table->addField('dateUpdated', $this->dateTime()->notNull());
            $table->addField('uid', $this->uid());

            $this->createTable($table->getDatabaseName(), $table->getFieldArray(), $table->getOptions());

            foreach ($table->getIndexes() as $index) {
                $this->createIndex(
                    $table->getName() . '_' . $index->getName(),
                    $table->getDatabaseName(),
                    $index->getColumns(),
                    $index->isUnique()
                );
            }
        }

        foreach ($this->defineTableData() as $table) {
            foreach ($table->getForeignKeys() as $foreignKey) {
                $this->addForeignKey(
                    $foreignKey->getName(),
                    $table->getDatabaseName(),
                    $foreignKey->getColumn(),
                    $foreignKey->getDatabaseReferenceTableName(),
                    $foreignKey->getReferenceColumn(),
                    $foreignKey->getOnDelete(),
                    $foreignKey->getOnUpdate()
                );
            }
        }

        return $this->afterInstall();
    }

    /**
     * @inheritdoc
     */
    final public function safeDown(): bool
    {
        if (!$this->beforeUninstall()) {
            return false;
        }

        $tables = $this->defineTableData();

        foreach ($tables as $table) {
            if (!\Craft::$app->db->tableExists($table->getDatabaseName())) {
                continue;
            }

            foreach ($table->getForeignKeys() as $foreignKey) {
                $this->dropForeignKey($foreignKey->getName(), $table->getDatabaseName());
            }
        }

        $tables = array_reverse($tables);

        /** @var Table $table */
        foreach ($tables as $table) {
            $this->dropTableIfExists($table->getDatabaseName());
        }

        return $this->afterUninstall();
    }

    /**
     * @return Table[]
     */
    abstract protected function defineTableData(): array;

    /**
     * Perform something before installing the tables
     */
    protected function beforeInstall(): bool
    {
        return true;
    }

    /**
     * Perform something after installing the tables
     */
    protected function afterInstall(): bool
    {
        return true;
    }

    /**
     * Perform something before installing the tables
     */
    protected function beforeUninstall(): bool
    {
        return true;
    }

    /**
     * Perform something after installing the tables
     */
    protected function afterUninstall(): bool
    {
        return true;
    }
}
