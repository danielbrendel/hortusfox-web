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
     * @param $name
     * @param $email
     * @param $lang
     * @param $chatcolor
     * @param $show_log
     * @return void
     * @throws \Exception
     */
    public static function editPreferences($name, $email, $lang, $chatcolor, $show_log)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, email = ?, lang = ?, chatcolor = ?, show_log = ? WHERE id = ?', [
                trim($name), trim($email), $lang, $chatcolor, $show_log, $user->get('id')
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getNameById($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();

            return $row->get('name');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getEMailById($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();

            return $row->get('email');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getChatColorForUser($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();

            $color = $row->get('chatcolor');
            if (($color === null) || (strlen($color) === 0)) {
                return '#7BC1DF';
            }

            return $color;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function updateLastSeenMsg($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_seen_msg = ? WHERE id = ?', [$id, $user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function updateOnlineStatus()
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_action = CURRENT_TIMESTAMP WHERE id = ?', [$user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function updateChatTyping()
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_typing = CURRENT_TIMESTAMP WHERE id = ?', [$user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function isAnyoneTypingInChat()
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $rows = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id <> ?', [$user->get('id')]);
            foreach ($rows as $row) {
                if ((static::isUserOnline($row->get('id'))) && (UtilsModule::isTyping($row->get('last_typing')))) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public static function isUserOnline($id)
    {
        try {
            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$row) {
                return false;
            }

            return Carbon::parse($row->get('last_action'))->diffInMinutes() <= env('APP_ONLINEMINUTELIMIT', 15);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getOnlineUsers()
    {
        try {
            $result = [];

            $rows = static::raw('SELECT * FROM `' . self::tableName() . '`');
            foreach ($rows as $row) {
                if (static::isUserOnline($row->get('id'))) {
                    $result[] = $row;
                }
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
        return 'users';
    }
}