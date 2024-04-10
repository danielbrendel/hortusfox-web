<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class CalendarClassModel extends \Asatru\Database\Model {
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
     * @param $ident
     * @return mixed
     * @throws \Exception
     */
    public static function findClass($ident)
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE ident = ?', [$ident])->first();
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
        return 'calendarclasses';
    }
}