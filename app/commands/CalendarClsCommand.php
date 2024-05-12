<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class CalendarClsCommand implements Asatru\Commands\Command {
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
            'color_background' => 'rgb(235, 163, 67)',
            'color_border' => 'rgb(230, 125, 50)'
        ],
        'treat' => [
            'name' => 'app.calendar_class_treat',
            'color_background' => 'rgb(200, 111, 111)',
            'color_border' => 'rgb(255, 150, 150)'
        ],
        'harvest' => [
            'name' => 'app.calendar_class_harvest',
            'color_background' => 'rgb(72, 243, 65)',
            'color_border' => 'rgb(180, 250, 155)'
        ],
        'other' => [
            'name' => 'app.calendar_class_other',
            'color_background' => 'rgb(150, 150, 150)',
            'color_border' => 'rgb(200, 200, 200)'
        ]
    ];

    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        if ($args->get(0)?->getValue(0) === '--force') {
            echo "Dropping previous calendar classes.\n";

            CalendarClassModel::raw('DELETE FROM `' . CalendarClassModel::tableName() . '`');
        }

        $entry_count = CalendarClassModel::raw('SELECT COUNT(*) AS `count` FROM `' . CalendarClassModel::tableName() . '`')->first()->get('count');
        if ($entry_count > 0) {
            return;
        }

        foreach (self::$class_table as $key => $value) {
            echo "Adding calendar class: {$key} {$value['name']} {$value['color_background']} {$value['color_border']}\n";

            CalendarClassModel::raw('INSERT INTO `' . CalendarClassModel::tableName() . '` (ident, name, color_background, color_border) VALUES(?, ?, ?, ?)', [
                $key, $value['name'], $value['color_background'], $value['color_border']
            ]);
        }
    }
}
    