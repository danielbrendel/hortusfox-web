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
     * @return int
     * @throws \Exception
     */
    public static function addEntry($plant, $content)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (plant, content) VALUES(?, ?)', [
                $plant, $content
            ]);

            LogModel::addLog($user->get('id'), $plant, 'add_plant_log', $content, url('/plants/details/' . $plant));

            $item = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();
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

            $item = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Invalid entry: ' . $id);
            }

            static::raw('UPDATE `' . self::tableName() . '` SET content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?', [
                $content, $item->get('id')
            ]);

            LogModel::addLog($user->get('id'), $item->get('id'), 'edit_plant_log', $content, url('/plants/details/' . $item->get('plant')));
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

            $item = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$item) {
                throw new \Exception('Invalid entry: ' . $id);
            }

            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [
                $item->get('id')
            ]);

            LogModel::addLog($user->get('id'), $item->get('id'), 'remove_plant_log', '', url('/plants/details/' . $item->get('plant')));
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
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE plant = ? AND id < ? ORDER BY id DESC LIMIT ' . $limit, [$plant, $paginate]);
            } else {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE plant = ? ORDER BY id DESC LIMIT ' . $limit, [$plant]);
            }
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
        return 'plantlog';
    }
}