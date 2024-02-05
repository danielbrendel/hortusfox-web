<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class LogModel extends \Asatru\Database\Model {
    const LOG_MAX_STRING_LENGTH = 1024;

    /**
     * @param $user
     * @param $target
     * @param $property
     * @param $value
     * @param $link
     * @return void
     * @throws \Exception
     */
    public static function addLog($user, $target, $property, $value, $link = null)
    {
        try {
            if ((is_string($value)) && (strlen($value) >= self::LOG_MAX_STRING_LENGTH)) {
                $value = substr($value, 0, self::LOG_MAX_STRING_LENGTH - 4) . '...';
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (user, target, property, value, link) VALUES(?, ?, ?, ?, ?)', [$user, $target, $property, $value, $link]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $user
     * @param $limit
     * @return array
     * @throws \Exception
     */
    public static function getHistory($user = null, $limit = 100)
    {
        try {
            $result = [];
            $history = null;

            if (($user) && (is_int($user))) {
                $history = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE user = ? ORDER BY created_at DESC LIMIT ' . $limit, [$user]);
            } else {
                $history = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY created_at DESC LIMIT ' . $limit);
            }

            foreach ($history as $entry) {
                $log_user = UserModel::getUserById($entry->get('user'));

                $result[] = [
                    'user' => $log_user->get('name'),
                    'target' => $entry->get('target'),
                    'property' => $entry->get('property'),
                    'value' => $entry->get('value'),
                    'link' => $entry->get('link'),
                    'date' => $entry->get('created_at')
                ];
            }

            return $result;
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
        return 'log';
    }
}