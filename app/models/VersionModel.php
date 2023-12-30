<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class VersionModel extends \Asatru\Database\Model {
    /**
     * @return string
     */
    public static function getSqlVersion()
    {
        try {
            return static::raw('SELECT VERSION() AS version')->first()->get('version');
        } catch (\Exception $e) {
            return '';
        }
    }
}