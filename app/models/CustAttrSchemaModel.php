<?php

/**
 * Class CustAttrSchemaModel
 * 
 * Manages global custom plant attribute schemas
 */ 
class CustAttrSchemaModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getAll($filter_active = true)
    {
        try {
            if ($filter_active) {
                return static::raw('SELECT * FROM `@THIS` WHERE active = 1');
            } else {
                return static::raw('SELECT * FROM `@THIS`');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $label
     * @param $datatype
     * @return void
     * @throws \Exception
     */
    public static function addSchema($label, $datatype)
    {
        try {
            if (static::schemaExists($label)) {
                throw new \Exception(__('app.schema_attribute_already_exists'));
            }

            static::raw('INSERT INTO `@THIS` (label, datatype, active) VALUES(?, ?, 1)', [
                $label, $datatype
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $label
     * @param $datatype
     * @param $active
     * @return void
     * @throws \Exception
     */
    public static function editSchema($id, $label, $datatype, $active)
    {
        try {
            if (static::schemaExists($label)) {
                throw new \Exception(__('app.schema_attribute_already_exists'));
            }

            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();

            static::raw('UPDATE `@THIS` SET label = ?, datatype = ?, active = ? WHERE id = ?', [
                $label, $datatype, $active, $id
            ]);

            CustPlantAttrModel::removeDataTypeAll($item->get('label'), $item->get('datatype'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $label
     * @return bool
     * @throws \Exception
     */
    public static function schemaExists($label)
    {
        try {
            $count = static::raw('SELECT COUNT(*) AS count FROM `@THIS` WHERE label = ? LIMIT 1', [$label])->first()->get('count');
            
            return $count > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function removeSchema($id)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}