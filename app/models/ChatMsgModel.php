<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class ChatMsgModel extends \Asatru\Database\Model {
    /**
     * @param $message
     * @return void
     * @throws \Exception
     */
    public static function addMessage($message)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $message = trim($message);

            static::raw('INSERT INTO `' . self::tableName() . '` (userId, message) VALUES(?, ?)', [
                $user->get('id'), $message
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getChat($limit = 50)
    {
        try {
            $result = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY created_at DESC LIMIT ' . $limit);

            if (count($result) > 0) {
                UserModel::updateLastSeenMsg($result->get(0)->get('id'));

                $lastsysmsg = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE system = 1 ORDER BY created_at DESC')->first();
                if ($lastsysmsg) {
                    UserModel::updateLastSeenSysMsg($lastsysmsg->get('id'));
                }
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getLatestMessages()
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $result = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id > ? ORDER BY created_at DESC', [($user->get('last_seen_msg')) ? $user->get('last_seen_msg') : 0]);

            if (($result) && (count($result) > 0)) {
                UserModel::updateLastSeenMsg($result->get(0)->get('id'));
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getLatestSystemMessage($limit = 0)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $limit_token = '';
            if ($limit > 0) {
                $limit_token = 'LIMIT ' . strval($limit);
            }

            $result = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE system = 1 AND id > ? ORDER BY created_at DESC ' . $limit_token, [($user->get('last_seen_sysmsg')) ? $user->get('last_seen_sysmsg') : 0]);
            if (($result) && (count($result) > 0)) {
                $msg = $result->get(count($result) - 1);

                UserModel::updateLastSeenSysMsg($msg->get('id'));
                
                return $msg;
            }

            return null;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getUnreadCount()
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $data = static::raw('SELECT COUNT(*) AS `count` FROM `' . self::tableName() . '` WHERE userId <> ? AND id > ? ORDER BY id ASC', [
                $user->get('id'), ($user->get('last_seen_msg')) ? $user->get('last_seen_msg') : 0
            ])->first();

            return $data->get('count');
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
        return 'chatmsg';
    }
}