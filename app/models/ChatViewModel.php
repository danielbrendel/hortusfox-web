<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class ChatViewModel extends \Asatru\Database\Model {
    /**
     * @param $userId
     * @param $messageId
     * @return bool
     * @throws \Exception
     */
    public static function handleNewMessage($userId, $messageId)
    {
        try {
            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE userId = ? AND messageId = ?', [$userId, $messageId])->first();
            if (!$row) {
                static::raw('INSERT INTO `' . self::tableName() . '` (userId, messageId) VALUES(?, ?)', [
                    $userId, $messageId
                ]);

                return true;
            }

            return false;
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
        return 'chatview';
    }
}