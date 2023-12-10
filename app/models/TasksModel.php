<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class TasksModel extends \Asatru\Database\Model {
    /**
     * @param $title
     * @param $description
     * @param $due_date
     * @return void
     * @throws \Exception
     */
    public static function addTask($title, $description = '', $due_date = null)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }
            
            static::raw('INSERT INTO `' . self::tableName() . '` (title, description, due_date) VALUES(?, ?, ?)', [$title, $description, $due_date]);

            LogModel::addLog($user->get('id'), 'tasks', 'add_task', $title);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $taskId
     * @param $title
     * @param $description
     * @param $due_date
     * @return void
     * @throws \Exception
     */
    public static function editTask($taskId, $title, $description, $due_date)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET title = ?, description = ?, due_date = ? WHERE id = ?', [$title, $description, $due_date, $taskId]);

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
            if (!$done) {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE done = ? ORDER BY -due_date DESC, created_at DESC LIMIT ' . $limit, [$done]);
            } else {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE done = ? ORDER BY created_at DESC LIMIT ' . $limit, [$done]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getOverdueTasks()
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE done = 0 AND DATE(due_date) < CURRENT_DATE ORDER BY due_date ASC');
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
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getTask($id)
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?')->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function cronjob()
    {
        try {
            $tasks = static::getOverdueTasks();
            foreach ($tasks as $task) {
                $ckdate = date('Y-m-d H:i:s', strtotime('+' . env('APP_OVERDUETASK_HOURS', 5) . ' hours', strtotime($task->get('due_date'))));
                
                $date1 = new DateTime('now');
                $date2 = new DateTime($ckdate);
                if ($date1 > $date2) {
                    OverdueInfoModel::inform($task, env('APP_CRONJOB_MAILLIMIT', 5));
                }
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
        return 'tasks';
    }
}