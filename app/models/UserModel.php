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
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE session = ? AND status = 1', [session_id()])->first();
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

            static::raw('UPDATE `' . self::tableName() . '` SET status = 1, session = ? WHERE id = ?', [session_id(), $data->get('id')]);
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
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE session = ? AND status = 1', [session_id()])->first();
            if (!$data) {
                throw new \Exception('No authenticated session.');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET status = 0, session = NULL WHERE id = ?', [$data->get('id')]);
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
            $mailobj->setView('mail/mailreset', [], ['workspace' => env('APP_WORKSPACE'), 'token' => $reset_token]);
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
     * @param $chatcolor
     * @param $show_log
     * @return void
     * @throws \Exception
     */
    public static function editPreferences($name, $email, $lang, $chatcolor, $show_log)
    {
        try {
            $user = static::getAuthUser();
            if (!$user) {
                throw new \Exception('User not authenticated');
            }

            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, email = ?, lang = ?, chatcolor = ?, show_log = ? WHERE id = ?', [
                trim($name), trim($email), $lang, $chatcolor, $show_log, $user->get('id')
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

            return Carbon::parse($row->get('last_action'))->diffInMinutes() <= env('APP_ONLINEMINUTELIMIT', 15);
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
     * @return void
     * @throws \Exception
     */
    public static function createUser($name, $email)
    {
        try {
            $password = substr(md5(random_bytes(55) . date('Y-m-d H:i:s')), 0, 10);
            
            static::raw('INSERT INTO `' . self::tableName() . '` (name, email, password) VALUES(?, ?, ?)', [
                $name, $email, password_hash($password, PASSWORD_BCRYPT)
            ]);

            $mailobj = new Asatru\SMTPMailer\SMTPMailer();
            $mailobj->setRecipient($email);
            $mailobj->setSubject(__('app.account_created'));
            $mailobj->setView('mail/mailacccreated', [], ['workspace' => env('APP_WORKSPACE'), 'password' => $password]);
            $mailobj->send();
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