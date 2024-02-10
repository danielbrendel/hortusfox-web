<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class InvGroupModel extends \Asatru\Database\Model {
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getAll()
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY token ASC');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $ident
     * @return string
     * @throws \Exception
     */
    public static function getLabel($ident)
    {
        try {
            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE token = ?', [$ident])->first();
            return $row->get('label');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $token
     * @return bool
     */
    public static function isValidGroupToken($token)
    {
        try {
            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE token = ?', [$token])->first();
            return $row !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $token
     * @param $label
     * @return void
     * @throws \Exception
     */
    public static function addItem($token, $label)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $token = trim(strtolower($token));

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE token = ?', [$token])->first();
            if ($row) {
                throw new \Exception('Token already in use: ' . $token);
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (token, label) VALUES(?, ?)', [
                $token, $label
            ]);

            LogModel::addLog($user->get('id'), 'inventory_groups', 'add_group_item', $token . '|' . $label, url('/inventory'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $what
     * @param $value
     * @return void
     * @throws \Exception
     */
    public static function editItem($id, $what, $value)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$row) {
                throw new \Exception('Item not found: ' . $id);
            }

            if ($what === 'token') {
                static::raw('UPDATE `' . self::tableName() . '` SET token = ? WHERE id = ?', [
                    trim(strtolower($value)), $id
                ]);
            } else if ($what === 'label') {
                static::raw('UPDATE `' . self::tableName() . '` SET label = ? WHERE id = ?', [
                    $value, $id
                ]);
            } else {
                throw new \Exception('Invalid column specifier: ' . $what);
            }

            LogModel::addLog($user->get('id'), 'inventory_groups', 'edit_group_item', $id . ':' . $what . '|' . $value, url('/inventory'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function removeItem($id)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ?', [$id])->first();
            if (!$row) {
                throw new \Exception('Invalid item: ' . $id);
            }

            if (InventoryModel::isGroupInUse($row->get('token'))) {
                throw new \Exception('Token is still in use: ' . $row->get('token'));
            }

            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$id]);

            LogModel::addLog($user->get('id'), 'inventory_groups', 'remove_group_item', $id, url('/inventory'));
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
        return 'invgroup';
    }
}