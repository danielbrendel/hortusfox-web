<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class CalendarModel extends \Asatru\Database\Model {
    public static $class_unknown = [
        'ident' => 'unknown',
        'name' => 'app.unknown_calendar_class',
        'color_background' => 'rgb(150, 150, 150)',
        'color_border' => 'rgb(100, 100, 100)'
    ];

    /**
     * @param $date_from
     * @param $date_till
     * @return mixed
     * @throws \Exception
     */
    public static function getItems($date_from = null, $date_till = null)
    {
        try {
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE DATE(date_from) >= ? AND DATE(date_till) <= ?', [$date_from, $date_till]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $date_from
     * @param $date_till
     * @param $class
     * @return void
     * @throws \Exception
     */
    public static function addItem($name, $date_from = null, $date_till = null, $class = null)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $class_item = CalendarClassModel::findClass($class);
            if (!$class_item) {
                $class_item = self::$class_unknown;
            } else {
                $class_item = $class_item->asArray();
            }

            static::raw('INSERT INTO `' . self::tableName() . '` (name, date_from, date_till, class_name, color_background, color_border, last_edited_user, last_edited_date) VALUES(?, ?, ?, ?, ?, ?, ?, ?)', [
                $name, $date_from, $date_till, $class, $class_item['color_background'], $class_item['color_border'], $user->get('id'), date('Y-m-d H:i:s')
            ]);

            TextBlockModule::addedCalendarItem($name, url('/calendar'));
            LogModel::addLog($user->get('id'), $date_from . ' - ' . $date_till, 'add_calendar', $name, url('/calendar'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $ident
     * @param $name
     * @param $date_from
     * @param $date_till
     * @param $class
     * @return void
     * @throws \Exception
     */
    public static function editItem($ident, $name, $date_from = null, $date_till = null, $class = null)
    {
        try {
            $user = UserModel::getAuthUser();
            if (!$user) {
                throw new \Exception('Invalid user');
            }

            $class_item = CalendarClassModel::findClass($class);
            if (!$class_item) {
                $class_item = self::$class_unknown;
            } else {
                $class_item = $class_item->asArray();
            }

            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, date_from = ?, date_till = ?, class_name = ?, color_background = ?, color_border = ?, last_edited_user = ?, last_edited_date = ? WHERE id = ?', [
                $name, $date_from, $date_till, $class, $class_item['color_background'], $class_item['color_border'], $user->get('id'), date('Y-m-d H:i:s'), $ident
            ]);

            TextBlockModule::editedCalendarItem($name, url('/calendar'));
            LogModel::addLog($user->get('id'), $date_from . ' - ' . $date_till, 'edit_calendar', $name, url('/calendar'));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $ident
     * @return void
     * @throws \Exception
     */
    public static function removeItem($ident)
    {
        try {
            static::raw('DELETE FROM `' . self::tableName() . '` WHERE id = ?', [$ident]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getTomorrowItems()
    {
        try {
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            return static::raw('SELECT * FROM `' . self::tableName() . '` WHERE DATE(date_from) = ? ORDER BY date_from ASC', [$tomorrow]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function cronjobReminder()
    {
        try {
            $items = static::getTomorrowItems();
            foreach ($items as $item) {
                CalendarInformerModel::inform($item, env('APP_CRONJOB_MAILLIMIT', 5));
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
        return 'calendar';
    }
}