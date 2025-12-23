<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class ThemeRemoveCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        if ($args->count() < 1) {
            echo "Invalid argument: please provide the name of the theme to remove";
            return;
        }

        $theme = $args->get(0)->getValue(0);

        echo "Attempting to remove {$theme}...\n";

        if (!is_dir(public_path() . '/themes/' . $theme)) {
            echo "Error: theme not found";
            return;
        }

        UtilsModule::clearFolder(public_path() . '/themes/' . $theme);

        echo "Done.\n";
    }
}
    