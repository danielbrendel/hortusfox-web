<?php

/**
 * Class LocationLogModel
 * 
 * Management of location specific log entries made by users
 */ 
class LocationLogModel extends \Asatru\Database\Model {
    /**
     * @param $location
     * @param $content
     * @return void
     * @throws \Exception
     */
    public static function addEntry($location, $content)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('INSERT INTO `@THIS` (location, content) VALUES(?, ?)', [
                $location, $content
            ]);

            LogModel::addLog($user->get('id'), $location, 'add_location_log', $content, url('/plants/location/' . $location));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $content
     * @return void
     * @throws \Exception
     */
    public static function editEntry($id, $content)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Invalid entry: ' . $id);
            }

            static::raw('UPDATE `@THIS` SET content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [
                $content, $item->get('id')
            ]);

            LogModel::addLog($user->get('id'), $item->get('id'), 'edit_location_log', $content, url('/plants/location/' . $item->get('location')));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function removeEntry($id)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Invalid entry: ' . $id);
            }

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [
                $item->get('id')
            ]);

            LogModel::addLog($user->get('id'), $item->get('id'), 'remove_location_log', '', url('/plants/location/' . $item->get('location')));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $location
     * @param $paginate
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getLogEntries($location, $paginate = null, $limit = 10)
    {
        try {
            if ($paginate) {
                return static::raw('SELECT * FROM `@THIS` WHERE location = ? AND id < ? ORDER BY id DESC LIMIT ' . $limit, [$location, $paginate]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE location = ? ORDER BY id DESC LIMIT ' . $limit, [$location]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}