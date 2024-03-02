<?php

/**
 * This class represents your module
 */
class TextBlockModule {
    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function newPlant($name, $url)
    {
        try {
            $text = __('tb.added_new_plant', ['name' => $name, 'url' => $url]);

            static::addToChat($text, 'x1fab4');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $message
     * @param $icon
     * @return void
     * @throws \Exception
     */
    private static function addToChat($message, $icon)
    {
        try {
            if (!app('chat_system')) {
                return;
            }

            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $icon = html_entity_decode('&#' . $icon, ENT_COMPAT | ENT_QUOTES);

            ChatMsgModel::raw('INSERT INTO `' . ChatMsgModel::tableName() . '` (userId, message, system, created_at) VALUES(?, ?, 1, CURRENT_TIMESTAMP)', [
                $user->get('id'),
                $icon . ' ' . $message
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
