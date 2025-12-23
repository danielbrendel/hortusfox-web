<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class ThemeListCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        $folders = scandir(public_path() . '/themes');

        echo "Installed themes:\n";

        foreach ($folders as $folder) {
            if (substr($folder, 0, 1) === '.') {
                continue;
            }

            $json = json_decode(file_get_contents(public_path() . '/themes/' . $folder . '/theme.json'));

            echo "- {$json->name} v{$json->version} | Author: {$json->author} | Contact: {$json->contact}\n";
        }

        echo "\n";
    }
}
    