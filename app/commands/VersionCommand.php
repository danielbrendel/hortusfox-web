<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class VersionCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        echo "Current product version: \033[32m" . config('version') . "\033[39m\n";
    }
}
    