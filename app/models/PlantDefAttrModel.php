<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class PlantDefAttrModel extends \Asatru\Database\Model {
    /**
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public static function isActive($name)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE name = ?', [$name])->first();
            if (!$data) {
                throw new \Exception('Attribute ' . $name . ' does not exist');
            }

            return $data->get('active');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return bool
     * @throws \Exception
     */
    public static function toggle($name)
    {
        try {
            static::raw('UPDATE `' . self::tableName() . '` SET active = NOT active WHERE name = ?', [$name]);
            
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE name = ?', [$name])->first()->get('active');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $value
     * @return void
     * @throws \Exception
     */
    public static function update($name, $value)
    {
        try {
            static::raw('UPDATE `' . self::tableName() . '` SET active = ? WHERE name = ?', [$value, $name]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getAll()
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '`');
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
        return 'plantdefattr';
    }
}