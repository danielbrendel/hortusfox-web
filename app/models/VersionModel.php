<?php

/**
 * Class VersionModel
 * 
 * Used to query MySQL version
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