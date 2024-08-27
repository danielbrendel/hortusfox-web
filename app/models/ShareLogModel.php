<?php

/**
 * Class ShareLogModel
 * 
 * Manages storage of shared photo log
 */ 
class ShareLogModel extends \Asatru\Database\Model {
    /**
     * @param $userId
     * @param $ident
     * @param $url
     * @param $asset
     * @return void
     * @throws \Exception
     */
    public static function addEntry($userId, $ident, $url, $asset)
    {
        try {
            static::raw('INSERT INTO `' . self::tableName() . '` (userId, ident, url, asset) VALUES(?, ?, ?, ?)', [
                $userId, $ident, $url, $asset
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $userId
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getForUser($userId, $limit = 0)
    {
        try {
            if ($limit) {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE userId = ? LIMIT ' . $limit, [$userId]);
            } else {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE userId = ?', [$userId]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $userId
     * @param $ident
     * @return void
     * @throws \Exception
     */
    public static function removeEntry($userId, $ident)
    {
        try {
            static::raw('DELETE FROM `' . self::tableName() . '` WHERE userId = ? AND ident = ?', [
                $userId, $ident
            ]);
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
        return 'sharelog';
    }
}