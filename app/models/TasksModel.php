<?php

/**
 * Class TasksModel
 * 
 * Manages tasks
 */ 
class TasksModel extends \Asatru\Database\Model {
    /**
     * @param $title
     * @param $description
     * @param $due_date
     * @param $recurring_time
     * @param $api
     * @return int
     * @throws \Exception
     */
    public static function addTask($title, $description = '', $due_date = null, $recurring_time = null, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }
            
            static::raw('INSERT INTO `@THIS` (title, description, due_date, recurring_time) VALUES(?, ?, ?, ?)', [$title, $description, $due_date, $recurring_time]);

            if (!$api) {
                LogModel::addLog($user->get('id'), 'tasks', 'add_task', $title, url('/tasks'));
                TextBlockModule::createdTask($title, url('/tasks'));
            }

            $latest = static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')->first();
            if ($latest) {
                return $latest->get('id');
            }

            return 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $taskId
     * @param $title
     * @param $description
     * @param $due_date
     * @param $done
     * @param $api
     * @return void
     * @throws \Exception
     */
    public static function editTask($taskId, $title, $description, $due_date, $done = null, $api = false)
    {
        try {
            $user = UserModel::getAuthUser();
            if ((!$user) && (!$api)) {
                throw new \Exception('Invalid user');
            }

            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$taskId])->first();

            if ($title === null) {
                $title = $item->get('title');
            }

            if ($description === null) {
                $description = $item->get('description');
            }

            if ($due_date === null) {
                $due_date = $item->get('due_date');
            } else if ($due_date === '') {
                $due_date = null;
            }

            if ($done === null) {                
                $done = $item->get('done');
            }

            static::raw('UPDATE `@THIS` SET title = ?, description = ?, due_date = ?, done = ? WHERE id = ?', [$title, $description, $due_date, $done, $taskId]);

            if (!$api) {
                LogModel::addLog($user->get('id'), 'tasks', 'edit_task', $title, url('/tasks#task-anchor-' . $taskId));
            }
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

            static::raw('UPDATE `@THIS` SET done = 1 WHERE id = ?', [$taskId]);

            $task = static::getTask($taskId);

            LogModel::addLog($user->get('id'), 'tasks', 'set_done', $taskId, url('/tasks'));
            TextBlockModule::completedTask($task->get('title'), url('/tasks'));
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

            static::raw('UPDATE `@THIS` SET done = NOT done WHERE id = ?', [$taskId]);

            $task = static::getTask($taskId);
            
            LogModel::addLog($user->get('id'), 'tasks', 'toggle_status', $taskId, url('/tasks'));

            if ($task->get('done')) {
                TextBlockModule::completedTask($task->get('title'), url('/tasks'));
            } else {
                TextBlockModule::reactivatedTask($task->get('title'), url('/tasks'));
            }
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
                return static::raw('SELECT * FROM `@THIS` WHERE done = ? ORDER BY -due_date DESC, updated_at DESC LIMIT ' . $limit, [$done]);
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE done = ? ORDER BY updated_at DESC LIMIT ' . $limit, [$done]);
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
            return static::raw('SELECT * FROM `@THIS` WHERE done = 0 AND recurring_time IS NULL AND DATE(due_date) < CURRENT_DATE ORDER BY due_date ASC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getTomorrowTasks()
    {
        try {
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            return static::raw('SELECT * FROM `@THIS` WHERE done = 0 AND DATE(due_date) = ? ORDER BY due_date ASC', [$tomorrow]);
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
            return static::raw('SELECT COUNT(*) AS count FROM `@THIS` WHERE done = 0')->first()->get('count');
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
            return static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$id])->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function cronjobOverdue()
    {
        try {
            $tasks = static::getOverdueTasks();
            foreach ($tasks as $task) {
                $ckdate = date('Y-m-d H:i:s', strtotime('+' . env('APP_OVERDUETASK_HOURS', 5) . ' hours', strtotime($task->get('due_date'))));
                
                $date1 = new DateTime('now');
                $date2 = new DateTime($ckdate);
                if ($date1 > $date2) {
                    TaskInformerModel::inform($task, 'overdue', env('APP_CRONJOB_MAILLIMIT', 5));
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function cronjobTomorrow()
    {
        try {
            $tasks = static::getTomorrowTasks();
            foreach ($tasks as $task) {
                TaskInformerModel::inform($task, 'tomorrow', env('APP_CRONJOB_MAILLIMIT', 5));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function cronjobRecurring()
    {
        try {
            $tasks = static::raw('SELECT * FROM `@THIS` WHERE done = 0 AND due_date IS NOT NULL AND recurring_time IS NOT NULL AND DATE_ADD(due_date, INTERVAL recurring_time HOUR) < CURRENT_DATE');
            foreach ($tasks as $task) {
                static::raw('UPDATE `@THIS` SET due_date = DATE_ADD(due_date, INTERVAL recurring_time HOUR) WHERE id = ?', [$task->get('id')]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $taskId
     * @return void
     * @throws \Exception
     */
    public static function removeTask($taskId)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE id = ?', [$taskId]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}