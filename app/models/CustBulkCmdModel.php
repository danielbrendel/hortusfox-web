<?php

/**
 * Class CustBulkCmdModel
 * 
 * Manages custom bulk commands for plants
 */ 
class CustBulkCmdModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     */
    public static function getCmdList()
    {
        try {
            return static::raw('SELECT * FROM `@THIS`');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $label
     * @param $attribute
     * @param $styles
     * @return void
     * @throws \Exception
     */
    public static function addCmd($label, $attribute, $styles)
    {
        try {
            static::raw('INSERT INTO `@THIS` (label, attribute, styles) VALUES(?, ?, ?)', [
                $label, $attribute, $styles
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $label
     * @param $attribute
     * @param $styles
     * @return void
     * @throws \Exception
     */
    public static function editCmd($id, $label, $attribute, $styles)
    {
        try {
            static::raw('UPDATE `@THIS` SET label = ?, attribute = ?, styles = ? WHERE id = ?', [
                $label, $attribute, $styles, $id
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
    public static function removeCmd($id)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}