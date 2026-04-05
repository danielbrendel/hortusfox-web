<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class BackupImportCommand implements Asatru\Commands\Command {
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

        if (count($args) === 0) {
            echo "Error: Please specify the import file (located in " . public_path() . '/backup' . ")\n";
            return;
        }

        $archive_file = $args->get(0)->getValue();

        if (count($args) > 1) {
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

        if (!file_exists(public_path() . '/backup/' . $archive_file)) {
            echo "Error: The file " . public_path() . '/backup/' . $archive_file . " does not exist\n";
            return;
        }

        echo "Importing backup from " . public_path() . '/backup/' . $archive_file . "...\n";

        if (substr($archive_file, -4) === '.zip') {
            $archive_file = substr($archive_file, 0, -4);
        }

        try {
            ImportModule::import($archive_file, $items);
        } catch (\Exception $e) {
            echo "Error: Import failed: {$e->getMessage()}\n";
            return;
        }

        echo "Import succeeded\n";
    }
}
    