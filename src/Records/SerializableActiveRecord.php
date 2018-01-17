<?php

namespace Solspace\Commons\Records;

use craft\db\ActiveRecord;

abstract class SerializableActiveRecord extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public function afterFind()
    {
        parent::afterFind();

        foreach ($this->getSerializableFields() as $fieldName) {
            if (!empty($this->$fieldName)) {
                $this->$fieldName = json_decode($this->$fieldName, true);
            }
        }
    }

    /**
     * Return the property names of serializable fields
     *
     * @return array
     */
    abstract protected function getSerializableFields(): array;
}
