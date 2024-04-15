<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class InventoryModel extends \Asatru\Database\Model {
    /**
     * @param $name
     * @param $description
     * @param $location
     * @param $group
     * @return int
     * @throws \Exception
     */
    public static function addItem($name, $description, $location, $group)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            if (!InvGroupModel::isValidGroupToken($group)) {
                throw new \Exception('Invalid group token: ' . $group);
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (name, group_ident, description, location, last_edited_user, last_edited_date) VALUES(?, ?, ?, ?, ?, CURRENT_TIMESTAMP)', [
                $name, $group, $description, $location, $user->get('id')
            ]);

            $row = static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY id DESC LIMIT 1')->first();

            if ((isset($_FILES['photo'])) && ($_FILES['photo']['error'] === UPLOAD_ERR_OK)) {
                $file_ext = UtilsModule::getImageExt($_FILES['photo']['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

                if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                    throw new \Exception('createThumbFile failed');
                }

                static::raw('UPDATE `' . self::tableName() . '` SET photo = ? WHERE id = ?', [
                    $file_name . '_thumb.' . $file_ext, $row->get('id')
                ]);
            }

            LogModel::addLog($user->get('id'), 'inventory', 'add_inventory_item', $name, url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
            TextBlockModule::createdInventoryItem($name, url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
            
            return $row->get('id');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $description
     * @param $location
     * @param $group
     * @return void
     * @throws \Exception
     */
    public static function editItem($id, $name, $description, $location, $group)
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

            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, group_ident = ?, location = ?, description = ? WHERE id = ?', [
                $name, $group, $location, $description, $row->get('id')
            ]);

            if ((isset($_FILES['photo'])) && ($_FILES['photo']['error'] === UPLOAD_ERR_OK)) {
                $file_ext = UtilsModule::getImageExt($_FILES['photo']['tmp_name']);

                if ($file_ext === null) {
                    throw new \Exception('File is not a valid image');
                }

                $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

                move_uploaded_file($_FILES['photo']['tmp_name'], public_path('/img/' . $file_name . '.' . $file_ext));

                if (!UtilsModule::createThumbFile(public_path('/img/' . $file_name . '.' . $file_ext), UtilsModule::getImageType($file_ext, public_path('/img/' . $file_name)), public_path('/img/' . $file_name), $file_ext)) {
                    throw new \Exception('createThumbFile failed');
                }

                if (file_exists(public_path('/img/' . $row->get('photo')))) {
                    unlink(public_path('/img/' . $row->get('photo')));
                }

                static::raw('UPDATE `' . self::tableName() . '` SET photo = ? WHERE id = ?', [
                    $file_name . '_thumb.' . $file_ext, $row->get('id')
                ]);
            }

            static::raw('UPDATE `' . self::tableName() . '` SET last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [
                $user->get('id'), $row->get('id')
            ]);

            LogModel::addLog($user->get('id'), 'inventory', 'edit_inventory_item', $name, url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws \Exception
     */
    public static function incAmount($id)
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

            $amount = $row->get('amount') + 1;
            
            static::raw('UPDATE `' . self::tableName() . '` SET amount = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [
                $amount, $user->get('id'), $row->get('id')
            ]);

            LogModel::addLog($user->get('id'), 'inventory', 'increment_inventory_item', $row->get('name'), url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));

            return $amount;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws \Exception
     */
    public static function decAmount($id)
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

            $amount = $row->get('amount') - 1;
            if ($amount < 0) {
                $amount = 0;
            }
            
            static::raw('UPDATE `' . self::tableName() . '` SET amount = ?, last_edited_user = ?, last_edited_date = CURRENT_TIMESTAMP WHERE id = ?', [
                $amount, $user->get('id'), $row->get('id')
            ]);

            LogModel::addLog($user->get('id'), 'inventory', 'decrement_inventory_item', $row->get('name'), url('/inventory?expand=' . $row->get('id') . '#anchor-item-' . $row->get('id')));

            return $amount;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getInventory()
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY group_ident, name ASC');
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

            if (file_exists(public_path('/img/' . $row->get('photo')))) {
                unlink(public_path('/img/' . $row->get('photo')));
            }

            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$row->get('id')]);

            LogModel::addLog($user->get('id'), 'inventory', 'remove_inventory_item', $row->get('name'), url('/inventory'));
            TextBlockModule::removedInventoryItem($row->get('name'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $group_ident
     * @return bool
     * @throws \Exception
     */
    public static function isGroupInUse($group_ident)
    {
        try {
            $row = static::raw('SELECT COUNT(*) AS `count` FROM `' . self::tableName() . '` WHERE group_ident = ?', [$group_ident])->first();
            if (!$row) {
                return false;
            }

            return $row->get('count') > 0;
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
        return 'inventory';
    }
}