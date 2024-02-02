<?php

/**
 * Class TaskInformerModel
 */ 
class TaskInformerModel extends \Asatru\Database\Model {
    /**
     * @param $userId
     * @param $taskId
     * @param $what
     * @return bool
     * @throws \Exception
     */
    public static function userInformed($userId, $taskId, $what)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE user = ? AND task = ? AND what = ?', [$userId, $taskId, $what])->first();
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
     * @param $what
     * @param $limit
     * @return void
     * @throws \Exception
     */
    public static function inform($task, $what, $limit = 5)
    {
        try {
            $users = UserModel::getAll();
            $count = 0;

            foreach ($users as $user) {
                if (($user->get('notify_tasks_' . $what)) && (!static::userInformed($user->get('id'), $task->get('id'), $what))) {
                    if ($count < $limit) {
                        $lang = $user->get('lang');
                        if ($lang === null) {
                            $lang = env('APP_LANG', 'en');
                        }

                        setLanguage($lang);

                        $mailobj = new Asatru\SMTPMailer\SMTPMailer();
                        $mailobj->setRecipient($user->get('email'));
                        $mailobj->setSubject(__('app.mail_info_task_' . $what));
                        $mailobj->setView('mail/task_' . $what, [], ['task' => $task, 'user' => $user]);
                        $mailobj->setProperties(mail_properties());
                        $mailobj->send();

                        static::raw('INSERT INTO `' . self::tableName() . '` (user, task, what) VALUES(?, ?, ?)', [$user->get('id'), $task->get('id'), $what]);
                        
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
        return 'taskinformer';
    }
}