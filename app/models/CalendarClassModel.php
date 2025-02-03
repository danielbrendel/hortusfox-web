<?php

/**
 * Class CalendarClassModel
 * 
 * Manages calendar classes
 */ 
class CalendarClassModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getAll()
    {
        try {
            return static::raw('SELECT * FROM `@THIS`');
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
            return static::raw('SELECT * FROM `@THIS` WHERE ident = ?', [$ident])->first();
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
            static::raw('INSERT INTO `@THIS` (ident, name, color_background, color_border) VALUES(?, ?, ?, ?)', [
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
            static::raw('UPDATE `@THIS` SET ident = ?, name = ?, color_background = ?, color_border = ? WHERE id = ?', [
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
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}