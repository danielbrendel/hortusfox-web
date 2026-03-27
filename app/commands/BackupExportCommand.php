<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class BackupExportCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        $items = [
            'locations' => true,
            'plants' => true,
            'gallery' => true,
            'tasks' => true,
            'inventory' => true,
            'calendar' => true,
        ];

        if (count($args) > 0) {
            $keys = array_keys($items);

            foreach ($items as $key => &$item) {
                $items[$key] = false;
            }

            array_map(function($key) use ($args, &$items) {
                foreach ($args as $arg) {
                    if (strstr($arg->getValue(), $key . '=') !== false) {
                        $eq = strpos($arg->getValue(), '=') + 1;
                        $val = substr($arg->getValue(), $eq);
                        
                        $items[$key] = (bool)intval($val);
                    }
                }
            }, $keys);
        }

        echo "Creating backup archive...\n";

        $output = BackupModule::start($items);
        if ($output) {
            $fullfilepath = public_path() . '/backup/' . $output;
            echo "Backup archive created: $fullfilepath\n";
        } else {
            echo "Error: Failed to create backup archive\n";
        }
    }
}
    