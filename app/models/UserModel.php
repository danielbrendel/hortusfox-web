<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class UserModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     */
    public static function getAuthUser()
    {
        try {
            $auth_token = ($_COOKIE['auth_token']) ?? null;

            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE token = ?', [$auth_token])->first();
            if (!$data) {
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $userId
     * @return mixed
     */
    public static function getUserById($userId)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$userId])->first();
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getCount()
    {
        try {
            return static::raw('SELECT COUNT(*) as count FROM `' . self::tableName() . '`')->first()->get('count');
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
        return 'users';
    }
}