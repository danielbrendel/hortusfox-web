<?php

/**
 * Class PlantTasksRefModel
 * 
 * Manage references between plants and tasks
 */ 
class PlantTasksRefModel extends \Asatru\Database\Model {
    /**
     * @param $plant
     * @param $task
     * @return int
     * @throws \Exception
     */
    public static function addReference($plant, $task)
    {
        try {
            static::raw('INSERT INTO `@THIS` (plant_id, task_id) VALUES(?, ?)', [$plant, $task]);

            return (int)static::raw('SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 1')?->first()?->get('id');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @return mixed
     * @throws \Exception
     */
    public static function getForPlant($plant)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE plant_id = ?', [$plant]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $task
     * @return mixed
     * @throws \Exception
     */
    public static function getForTask($task)
    {
        try {
            return static::raw('SELECT * FROM `@THIS` WHERE task_id = ? LIMIT 1', [$task])?->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $task
     * @return bool
     * @throws \Exception
     */
    public static function hasPlantReference($task)
    {
        try {
            return (int)static::raw('SELECT COUNT(*) AS `count` FROM `@THIS` WHERE task_id = ?', [$task])?->first()?->get('count') > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @return bool
     * @throws \Exception
     */
    public static function hasTaskReference($plant)
    {
        try {
            return (int)static::raw('SELECT COUNT(*) AS `count` FROM `@THIS` WHERE plant_id = ?', [$plant])?->first()?->get('count') > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @param $task
     * @return void
     * @throws \Exception
     */
    public static function removeForRelationship($plant, $task)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE plant_id = ? AND task_id = ?', [$plant, $task]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $plant
     * @return void
     * @throws \Exception
     */
    public static function removeForPlant($plant)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE plant_id = ?', [$plant]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $task
     * @return void
     * @throws \Exception
     */
    public static function removeForTask($task)
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE task_id = ?', [$task]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}