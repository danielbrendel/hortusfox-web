<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class LocationsModel extends \Asatru\Database\Model {
    /**
     * @param $only_active
     * @return mixed
     * @throws \Exception
     */
    public static function getAll($only_active = true)
    {
        try {
            if ($only_active) {
                return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE active = 1 ORDER BY name ASC');
            } else {
                return static::raw('SELECT * FROM `' . self::tableName() . '` ORDER BY name ASC');
            }
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
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ? LIMIT 1', [$id])->first()?->get('name');
        } catch (\Exception $e) {
            throw $e;
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
     * @param $icon
     * @return void
     * @throws \Exception
     */
    public static function addLocation($name, $icon)
    {
        try {
            static::raw('INSERT INTO `' . self::tableName() . '` (name, icon) VALUES(?, ?)', [
                $name, $icon
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $icon
     * @param $active
     * @return void
     * @throws \Exception
     */
    public static function editLocation($id, $name, $icon, $active)
    {
        try {
            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, icon = ?, active = ? WHERE id = ?', [
                $name, $icon, $active, $id
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $target
     * @return void
     * @throws \Exception
     */
    public static function removeLocation($id, $target)
    {
        try {
            if ((static::getCount() <= 1) && (PlantsModel::getPlantCount($id))) {
                throw new \Exception(__('app.error_room_not_empty'));
            }

            PlantsModel::migratePlants($id, $target);

            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$id]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function isActive($id)
    {
        try {
            $data = static::raw('SELECT * FROM `' . self::tableName() . '` WHERE id = ? AND active = 1', [$id])->first();
            return $data !== null;
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
        return 'locations';
    }
}