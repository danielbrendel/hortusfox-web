<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class MigrationUpgrade implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        try {
            $version = config('version');
            
            $history = [];
            if (file_exists(app_path() . '/migrations/verhist.json')) {
                $history = json_decode(file_get_contents(app_path() . '/migrations/verhist.json'));
            }
            
            if (strpos($version, '.') === false) {
                $version .= '.0';
            }

            $prev_ver = (count($history) > 0) ? $history[count($history) - 1] : $version;
            
            echo "Upgrading to \033[94m{$version}\033[39m...\n";

            list($startMajor, $startMinor) = explode('.', $prev_ver);
            list($endMajor, $endMinor) = explode('.', $version);

            for ($major = $startMajor; $major <= $endMajor; $major++) {
                $minStart = ($major == $startMajor) ? $startMinor : 0;
                $minEnd = ($major == $endMajor) ? $endMinor : 9;

                for ($minor = $minStart; $minor <= $minEnd; $minor++) {
                    if (UpgradeModule::upgrade($major . '.' . $minor)) {
                        if (!in_array($version, $history)) {
                            $history[] = $version;
                        }

                        file_put_contents(app_path() . '/migrations/verhist.json', json_encode($history));

                        echo "\033[39m[\033[93m$major.$minor\033[39m] \033[32mDone!\033[39m\n";
                    } else {
                        echo "\033[93m[$major.$minor] Nothing to migrate or upgrade.\033[39m\n";
                    }
                }
            }
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
    