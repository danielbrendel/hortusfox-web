<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class CacheClearCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        echo "Clearing cache...\n";

        try {
            CacheModel::clear();

            echo "\033[32mDone!\033[39m\n";
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
    