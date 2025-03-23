<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class MigrationSpecific implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        $version = $args?->get(0)?->getValue();

        try {
            UpgradeModule::upgrade($version);

            echo "\033[39m[\033[93m$version\033[39m] \033[32mDone!\033[39m\n";
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
    