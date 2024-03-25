<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class CalendarModel extends \Asatru\Database\Model {
    public static $class_table = [
        'water' => [
            'name' => 'app.calendar_class_water',
            'color_background' => 'rgb(76, 135, 195)',
            'color_border' => 'rgb(131, 183, 251)'
        ],
        'repot' => [
            'name' => 'app.calendar_class_repot',
            'color_background' => 'rgb(150, 115, 74)',
            'color_border' => 'rgb(222, 183, 143)'
        ],
        'fertilise' => [
            'name' => 'app.calendar_class_fertilise',
            'color_background' => 'rgb(135, 195, 102)',
            'color_border' => 'rgb(205, 240, 167)'
        ],
        'purchase' => [
            'name' => 'app.calendar_class_purchase',
            'color_background' => 'rgb(230, 220, 90)',
            'color_border' => 'rgb(255, 250, 185)'
        ],
        'cut' => [
            'name' => 'app.calendar_class_cut',
            'color_background' => 'rgb(150, 150, 150)',
            'color_border' => 'rgb(200, 200, 200)'
        ],
        'treat' => [
            'name' => 'app.calendar_class_treat',
            'color_background' => 'rgb(200, 111, 111)',
            'color_border' => 'rgb(255, 150, 150)'
        ]
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

            static::raw('INSERT INTO `' . self::tableName() . '` (name, date_from, date_till, class_name, color_background, color_border, last_edited_user, last_edited_date) VALUES(?, ?, ?, ?, ?, ?, ?, ?)', [
                $name, $date_from, $date_till, $class, self::$class_table[$class]['color_background'], self::$class_table[$class]['color_border'], $user->get('id'), date('Y-m-d H:i:s')
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

            static::raw('UPDATE `' . self::tableName() . '` SET name = ?, date_from = ?, date_till = ?, class_name = ?, color_background = ?, color_border = ?, last_edited_user = ?, last_edited_date = ? WHERE id = ?', [
                $name, $date_from, $date_till, $class, self::$class_table[$class]['color_background'], self::$class_table[$class]['color_border'], $user->get('id'), date('Y-m-d H:i:s'), $ident
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
     * @return array
     * @throws \Exception
     */
    public static function getClasses()
    {
        try {
            return self::$class_table;
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