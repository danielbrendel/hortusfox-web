<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class AttributesCommand implements Asatru\Commands\Command {
    public static $default_plant_attributes = [
        'last_watered',
        'last_repotted',
        'last_fertilised',
        'perennial',
        'annual',
        'cutting_month',
        'date_of_purchase',
        'humidity',
        'light_level',
        'health_state'
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
            echo "Dropping previous plant attributes.\n";

            PlantDefAttrModel::raw('DELETE FROM `' . PlantDefAttrModel::tableName() . '`');
        }

        $entry_count = PlantDefAttrModel::raw('SELECT COUNT(*) AS `count` FROM `' . PlantDefAttrModel::tableName() . '`')->first()->get('count');
        if ($entry_count > 0) {
            return;
        }

        foreach (self::$default_plant_attributes as $def_attribute) {
            echo "Adding default plant attribute: {$def_attribute}\n";

            PlantDefAttrModel::raw('INSERT INTO `' . PlantDefAttrModel::tableName() . '` (name, active) VALUES(?, 1)', [$def_attribute]);
        }
    }
}
    