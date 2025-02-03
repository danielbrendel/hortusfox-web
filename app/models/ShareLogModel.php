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
     * @param $title
     * @return void
     * @throws \Exception
     */
    public static function addEntry($userId, $ident, $url, $asset, $title)
    {
        try {
            static::raw('INSERT INTO `@THIS` (userId, ident, url, asset, title) VALUES(?, ?, ?, ?, ?)', [
                $userId, $ident, $url, $asset, $title
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $userId
     * @param $paginate
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getForUser($userId, $paginate = null, $limit = 10)
    {
        try {
            if ($paginate) {
                return static::raw('SELECT * FROM `@THIS` WHERE userId = ? AND id < ? ORDER BY id DESC LIMIT ' . $limit, [$userId, $paginate]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE userId = ? ORDER BY id DESC LIMIT ' . $limit, [$userId]);
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
            static::raw('DELETE FROM `@THIS` WHERE userId = ? AND ident = ?', [
                $userId, $ident
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}