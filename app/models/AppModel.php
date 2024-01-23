<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class AppModel extends \Asatru\Database\Model {
    /**
     * @param $name
     * @param $fallback
     * @param $profile
     * @return mixed
     * @throws \Exception
     */
    public static function query($name, $fallback = null, $profile = 1)
    {
        try {
            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$profile])->first();
            if (!$item) {
                return $fallback;
            }

            return $item->get($name);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $value
     * @return void
     * @throws \Exception
     */
    public static function updateSingle($name, $value)
    {
        try {
            static::raw('UPDATE `@THIS` SET ' . $name . ' = ?', [$value]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $set
     * @return void
     * @throws \Exception
     */
    public static function updateSet($set)
    {
        try {
            foreach ($set as $key => $value) {
                static::raw('UPDATE `@THIS` SET ' . $key . ' = ?', [$value]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}