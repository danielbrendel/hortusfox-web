<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class UserModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     */
    public static function getAuthUser()
    {
        try {
            $session = SessionModel::findSession(session_id());
            if (!$session) {
                return null;
            }

            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$session->get('userId')])->first();
            if (!$data) {
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $email
     * @param $password
     * @return void
     * @throws \Exception
     */
    public static function login($email, $password)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE email = ?', [$email])->first();
            if (!$data) {
                throw new \Exception(__('app.user_not_found', ['email' => $email]));
            }

            if (!password_verify($password, $data->get('password'))) {
                throw new \Exception(__('app.password_mismatch'));
            }

            SessionModel::loginSession($data->get('id'), session_id());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function logout()
    {
        try {
            SessionModel::logoutSession(session_id());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $password
     * @return void
     * @throws \Exception
     */
    public static function updatePassword($password)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $password = password_hash($password, PASSWORD_BCRYPT);

            static::raw('UPDATE `' . self::tableName() . '` SET password = ? WHERE id = ?', [$password, $user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $email
     * @return void
     * @throws \Exception
     */
    public static function restorePassword($email)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE email = ?', [$email])->first();
            if (!$data) {
                throw new \Exception(__('app.user_not_found', ['email' => $email]));
            }

            $reset_token = md5(random_bytes(55) . date('Y-m-d H:i:s'));

            static::raw('UPDATE `' . self::tableName() . '` SET password_reset = ? WHERE id = ?', [$reset_token, $data->get('id')]);

            $mailobj = new Asatru\SMTPMailer\SMTPMailer();
            $mailobj->setRecipient($email);
            $mailobj->setSubject(__('app.reset_password'));
            $mailobj->setView('mail/mailreset', [], ['workspace' => app('workspace'), 'token' => $reset_token]);
            $mailobj->setProperties(mail_properties());
            $mailobj->send();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $reset_token
     * @param $password
     * @return void
     * @throws \Exception
     */
    public static function resetPassword($reset_token, $password)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE password_reset = ?', [$reset_token])->first();
            if (!$data) {
                throw new \Exception('Token not found');
            }

            $password = password_hash($password, PASSWORD_BCRYPT);

            static::raw('UPDATE `' . self::tableName() . '` SET password_reset = NULL, password = ? WHERE id = ?', [$password, $data->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $userId
     * @return mixed
     */
    public static function getUserById($userId)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$userId])->first();
            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return bool
     */
    public static function isCurrentlyAdmin()
    {
        try {
            $user = static::getAuthUser();
            if ((!$user) || (!$user->get('admin'))) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function getCount()
    {
        try {
            return static::raw('SELECT COUNT(*) as count FROM `' . self::tableName() . '`')->first()->get('count');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $email
     * @param $lang
     * @param $theme
     * @param $chatcolor
     * @param $show_log
     * @param $show_calendar_view
     * @param $show_plant_id
     * @param $notify_tasks_overdue
     * @param $notify_tasks_tomorrow
     * @param $notify_calendar_reminder
     * @param $show_plants_aoru
     * @return void
     * @throws \Exception
     */
    public static function editPreferences($name, $email, $lang, $theme, $chatcolor, $show_log, $show_calendar_view, $show_plant_id, $notify_tasks_overdue, $notify_tasks_tomorrow, $notify_calendar_reminder, $show_plants_aoru)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, email = ?, lang = ?, theme = ?, chatcolor = ?, show_log = ?, show_calendar_view = ?, show_plant_id = ?, notify_tasks_overdue = ?, notify_tasks_tomorrow = ?, notify_calendar_reminder = ?, show_plants_aoru = ? WHERE id = ?', [
                trim($name), trim($email), $lang, $theme, $chatcolor, $show_log, $show_calendar_view, $show_plant_id, $notify_tasks_overdue, $notify_tasks_tomorrow, $notify_calendar_reminder, (int)$show_plants_aoru, $user->get('id')
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $notes
     * @return void
     * @throws \Exception
     */
    public static function saveNotes($notes)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET notes = ? WHERE id = ?', [
                trim($notes), $user->get('id')
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getNameById($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();

            return $row->get('name');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getEMailById($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();

            return $row->get('email');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public static function getChatColorForUser($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();

            $color = $row->get('chatcolor');
            if (($color === null) || (strlen($color) === 0)) {
                return '#7BC1DF';
            }

            return $color;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function updateLastSeenMsg($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_seen_msg = ? WHERE id = ?', [$id, $user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function updateLastSeenSysMsg($id)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_seen_sysmsg = ? WHERE id = ?', [$id, $user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function updateOnlineStatus()
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_action = CURRENT_TIMESTAMP WHERE id = ?', [$user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function updateChatTyping()
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_typing = CURRENT_TIMESTAMP WHERE id = ?', [$user->get('id')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public static function isAnyoneTypingInChat()
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            $rows = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id <> ?', [$user->get('id')]);
            foreach ($rows as $row) {
                if ((static::isUserOnline($row->get('id'))) && (UtilsModule::isTyping($row->get('last_typing')))) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public static function isUserOnline($id)
    {
        try {
            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$row) {
                return false;
            }

            return Carbon::parse($row->get('last_action'))->diffInMinutes() <= app('chat_timelimit', 15);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getOnlineUsers()
    {
        try {
            $result = [];

            $rows = static::raw('SELECT * FROM `' . self::tableName() . '`');
            foreach ($rows as $row) {
                if (static::isUserOnline($row->get('id'))) {
                    $result[] = $row;
                }
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '`');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $email
     * @param $sendmail
     * @return mixed
     * @throws \Exception
     */
    public static function createUser($name, $email, $sendmail)
    {
        try {
            $password = substr(md5(random_bytes(55) . date('Y-m-d H:i:s')), 0, 10);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Invalid E-Mail given: ' . $email);
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (name, email, password) VALUES(?, ?, ?)', [
                $name, $email, password_hash($password, PASSWORD_BCRYPT)
            ]);

            if ($sendmail) {
                $mailobj = new Asatru\SMTPMailer\SMTPMailer();
                $mailobj->setRecipient($email);
                $mailobj->setSubject(__('app.account_created'));
                $mailobj->setView('mail/mailacccreated', [], ['workspace' => app('workspace'), 'password' => $password]);
                $mailobj->setProperties(mail_properties());
                $mailobj->send();

                return null;
            }
            
            return $password;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $email
     * @param $admin
     * @return void
     * @throws \Exception
     */
    public static function updateUser($id, $name, $email, $admin)
    {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Invalid E-Mail given: ' . $email);
            }
            
            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, email = ?, admin = ? WHERE id = ?', [
                $name, $email, $admin, $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function removeUser($id)
    {
        try {
            SessionModel::clearForUser($id);

            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [
                $id
            ]);
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
        return 'users';
    }
}