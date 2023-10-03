<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class TasksModel extends \Asatru\Database\Model {
    /**
     * @param $title
     * @param $description
     * @return void
     * @throws \Exception
     */
    public static function addTask($title, $description = '')
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (title, description) VALUES(?, ?)', [$title, $description]);

            LogModel::addLog($user->get('id'), 'tasks', 'add_task', $title);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $taskId
     * @param $title
     * @param $description
     * @return void
     * @throws \Exception
     */
    public static function editTask($taskId, $title, $description)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET title = ?, description = ? WHERE id = ?', [$title, $description, $taskId]);

            LogModel::addLog($user->get('id'), 'tasks', 'edit_task', $title);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $taskId
     * @return void
     * @throws \Exception
     */
    public static function setTaskDone($taskId)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET done = 1 WHERE id = ?', [$taskId]);

            LogModel::addLog($user->get('id'), 'tasks', 'set_done', $taskId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $taskId
     * @return void
     * @throws \Exception
     */
    public static function toggleTaskStatus($taskId)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET done = NOT done WHERE id = ?', [$taskId]);

            LogModel::addLog($user->get('id'), 'tasks', 'toggle_status', $taskId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $done
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function getTasks($done = false, $limit = 100)
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE done = ? ORDER BY created_at DESC LIMIT ' . $limit, [$done]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getOpenTaskCount()
    {
        try {
            return static::raw('SELECT COUNT(*) AS count FROM `' . self::tableName() . '` WHERE done = 0')->first()->get('count');
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
        return 'tasks';
    }
}