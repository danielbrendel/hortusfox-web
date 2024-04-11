<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class CalendarInformerModel extends \Asatru\Database\Model {
    /**
     * @param $userId
     * @param $itemId
     * @return bool
     * @throws \Exception
     */
    public static function userInformed($userId, $itemId)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE user = ? AND item = ?', [$userId, $itemId])->first();
            if ($data) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $item
     * @param $limit
     * @return void
     * @throws \Exception
     */
    public static function inform($item, $limit = 5)
    {
        try {
            $users = UserModel::getAll();
            $count = 0;

            foreach ($users as $user) {
                if (($user->get('notify_calendar_reminder')) && (!static::userInformed($user->get('id'), $item->get('id')))) {
                    if ($count < $limit) {
                        $lang = $user->get('lang');
                        if ($lang === null) {
                            $lang = env('APP_LANG', 'en');
                        }

                        setLanguage($lang);
                        
                        $mailobj = new Asatru\SMTPMailer\SMTPMailer();
                        $mailobj->setRecipient($user->get('email'));
                        $mailobj->setSubject(__('app.mail_info_calendar_reminder'));
                        $mailobj->setView('mail/calendar_reminder', [], ['item' => $item, 'user' => $user]);
                        $mailobj->setProperties(mail_properties());
                        $mailobj->send();

                        static::raw('INSERT INTO `' . self::tableName() . '` (user, item) VALUES(?, ?)', [$user->get('id'), $item->get('id')]);
                        
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
        return 'calendarinformer';
    }
}