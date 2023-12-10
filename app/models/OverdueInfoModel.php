<?php

/**
 * Class OverdueInfoModel
 */ 
class OverdueInfoModel extends \Asatru\Database\Model {
    /**
     * @param $userId
     * @param $taskId
     * @return bool
     * @throws \Exception
     */
    public static function userInformed($userId, $taskId)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE user = ? AND task = ?', [$userId, $taskId])->first();
            if ($data) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $task
     * @param $limit
     * @return void
     * @throws \Exception
     */
    public static function inform($task, $limit = 5)
    {
        try {
            $users = UserModel::getAll();
            $count = 0;

            foreach ($users as $user) {
                if (($user->get('notify_overdue_tasks')) && (!static::userInformed($user->get('id'), $task->get('id')))) {
                    if ($count < $limit) {
                        $lang = $user->get('lang');
                        if ($lang === null) {
                            $lang = env('APP_LANG', 'en');
                        }

                        setLanguage($lang);

                        $mailobj = new Asatru\SMTPMailer\SMTPMailer();
                        $mailobj->setRecipient($user->get('email'));
                        $mailobj->setSubject(__('app.info_task_is_overdue'));
                        $mailobj->setView('mail/taskoverdue', [], ['task' => $task, 'user' => $user]);
                        $mailobj->send();

                        static::raw('INSERT INTO `' . self::tableName() . '` (user, task) VALUES(?, ?)', [$user->get('id'), $task->get('id')]);
                        
                        $count++;
                    }
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
        return 'overdueinfo';
    }
}