<?php

namespace Solspace\Commons\Records;

use craft\db\ActiveRecord;

/**
 * Class SerializableActiveRecord
 *
 * @deprecated Completely useless since Craft 3.4 changes
 */
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
                $decodedValue = \GuzzleHttp\json_decode($this->$fieldName, true);

                $this->$fieldName = $decodedValue;
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
