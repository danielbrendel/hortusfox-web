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
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function plantToHistory($name, $url)
    {
        try {
            $text = __('tb.moved_plant_to_history', ['name' => $name, 'url' => $url, 'history' => app('history_name')]);

            static::addToChat($text, 'x1f570');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function plantFromHistory($name, $url)
    {
        try {
            $text = __('tb.restored_plant_from_history', ['name' => $name, 'url' => $url, 'history' => app('history_name')]);

            static::addToChat($text, 'x1f570');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return void
     * @throws \Exception
     */
    public static function deletePlant($name)
    {
        try {
            $text = __('tb.deleted_plant', ['name' => $name]);

            static::addToChat($text, 'x1fab4');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function createdTask($name, $url)
    {
        try {
            $text = __('tb.created_task', ['name' => $name, 'url' => $url]);

            static::addToChat($text, 'x1f4dc');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function completedTask($name, $url)
    {
        try {
            $text = __('tb.completed_task', ['name' => $name, 'url' => $url]);

            static::addToChat($text, 'x1f4dc');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function reactivatedTask($name, $url)
    {
        try {
            $text = __('tb.reactivated_task', ['name' => $name, 'url' => $url]);

            static::addToChat($text, 'x1f4dc');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function createdInventoryItem($name, $url)
    {
        try {
            $text = __('tb.created_inventory_item', ['name' => $name, 'url' => $url]);

            static::addToChat($text, 'x1f4d6');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @return void
     * @throws \Exception
     */
    public static function removedInventoryItem($name)
    {
        try {
            $text = __('tb.removed_inventory_item', ['name' => $name]);

            static::addToChat($text, 'x1f4d6');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function addedCalendarItem($name, $url)
    {
        try {
            $text = __('tb.added_calendar_item', ['name' => $name, 'url' => $url]);

            static::addToChat($text, 'x1f4c5');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $url
     * @return void
     * @throws \Exception
     */
    public static function editedCalendarItem($name, $url)
    {
        try {
            $text = __('tb.edited_calendar_item', ['name' => $name, 'url' => $url]);

            static::addToChat($text, 'x1f4c5');
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
