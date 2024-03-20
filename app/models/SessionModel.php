<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class SessionModel extends \Asatru\Database\Model {
    /**
     * @param $userId
     * @param $session
     * @return void
     * @throws \Exception
     */
    public static function loginSession($userId, $session)
    {
        try {
            if (!static::hasSession($userId, $session)) {
                static::raw('INSERT INTO `@THIS` (userId, session, status) VALUES(?, ?, 1)', [
                    $userId, $session
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $session
     * @return void
     * @throws \Exception
     */
    public static function logoutSession($session)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE session = ? AND status = 1', [
                $session
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $session
     * @return mixed
     * @throws \Exception
     */
    public static function findSession($session)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE session = ? AND status = 1', [
                $session
            ])->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $userId
     * @param $session
     * @return bool
     * @throws \Exception
     */
    public static function hasSession($userId, $session)
    {
        try {
            return static::raw('SELECT COUNT(*) AS `count` FROM `@THIS` WHERE userId = ? AND session = ?', [
                $userId, $session
            ])->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $userId
     * @return void
     * @throws \Exception
     */
    public static function clearForUser($userId)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE userId = ?', [
                $userId
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}