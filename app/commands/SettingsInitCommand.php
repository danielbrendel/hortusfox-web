<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class SettingsInitCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        try {
            AppModel::raw('INSERT INTO `@THIS` (id, workspace, language, cronjob_pw, created_at) VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP)', [
                env('APP_WORKSPACE'), env('APP_LANG'), md5(random_bytes(55) . date('Y-m-d H:i:s'))
            ]);

            echo "\033[32mAdded initial app settings\033[39m\n";
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
