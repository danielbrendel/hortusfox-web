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
     * @param $ident
     * @param $name
     * @param $color_background
     * @param $color_border
     * @return void
     * @throws \Exception
     */
    public static function addClass($ident, $name, $color_background, $color_border)
    {
        try {
            static::raw('INSERT INTO `' . self::tableName() . '` (ident, name, color_background, color_border) VALUES(?, ?, ?, ?)', [
                $ident, $name, $color_background, $color_border
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $ident
     * @param $name
     * @param $color_background
     * @param $color_border
     * @return void
     * @throws \Exception
     */
    public static function editClass($id, $ident, $name, $color_background, $color_border)
    {
        try {
            static::raw('UPDATE `' . self::tableName() . '` SET ident = ?, name = ?, color_background = ?, color_border = ? WHERE id = ?', [
                $ident, $name, $color_background, $color_border, $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function removeClass($id)
    {
        try {
            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$id]);
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