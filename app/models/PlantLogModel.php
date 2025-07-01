<?php

/**
 * Class PlantLogModel
 * 
 * Management of plant specific log entries made by users
 */ 
class PlantLogModel extends \Asatru\Database\Model {
    /**
     * @param $plant
     * @param $content
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function addEntry($plant, $content, $api = false)
    {
        try {
            $user = null;

            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            static::raw('INSERT INTO `@THIS` (plant, content) VALUES(?, ?)', [
                $plant, $content
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $plant, 'add_plant_log', $content, url('/plants/details/' . $plant));
            }

            $item = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();
            if ($item) {
                return $item->get('id');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $content
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editEntry($id, $content, $api = false)
    {
        try {
            $user = null;

            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Invalid entry: ' . $id);
            }

            static::raw('UPDATE `@THIS` SET content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [
                $content, $item->get('id')
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $item->get('id'), 'edit_plant_log', $content, url('/plants/details/' . $item->get('plant')));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function removeEntry($id, $api = false)
    {
        try {
            $user = null;

            if (!$api) {
                $user = UserModel::getAuthUser();
                if (!$user) {
                    throw new \Exception('Invalid user');
                }
            }

            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Invalid entry: ' . $id);
            }

            static::raw('DELETE FROM `@THIS` WHERE id = ?', [
                $item->get('id')
            ]);

            if (!$api) {
                LogModel::addLog($user->get('id'), $item->get('id'), 'remove_plant_log', '', url('/plants/details/' . $item->get('plant')));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @param $paginate
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getLogEntries($plant, $paginate = null, $limit = 10)
    {
        try {
            if ($paginate) {
                return static::raw('SELECT * FROM `@THIS` WHERE plant = ? AND id < ? ORDER BY id DESC LIMIT ' . $limit, [$plant, $paginate]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE plant = ? ORDER BY id DESC LIMIT ' . $limit, [$plant]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}