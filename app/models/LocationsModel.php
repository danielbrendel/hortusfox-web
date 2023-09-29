<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class LocationsModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getAll()
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE active = 1 ORDER BY name ASC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getNameById($id)
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ? LIMIT 1', [$id])->first()?->get('name');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the associated table name of the migration
     * 
     * @return string
     */
    public static function tableName()
    {
        return 'locations';
    }
}