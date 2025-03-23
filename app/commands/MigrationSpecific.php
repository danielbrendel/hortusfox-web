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

        if (strpos($version, '.') === false) {
            $version .= '.0';
        }

        try {
            if (UpgradeModule::upgrade($version)) {
                echo "\033[39m[\033[93m$version\033[39m] \033[32mDone!\033[39m\n";
            } else {
                echo "\033[93m[$version] Nothing to migrate or upgrade.\033[39m\n";
            }
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
    