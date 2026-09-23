<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class MigrationVersion implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        try {
            $current_version = config('version');
            $next_version = strval(floatval($current_version) + 0.1);

            if (strpos($current_version, '.') === false) {
                $current_version = $current_version . '.0';
            }

            if (strpos($next_version, '.') === false) {
                $next_version = $next_version . '.0';
            }

            $files = ['/.env', '/.env.example', '/.env.testing', '/public/install/index.php', '/scripts/install.dnys'];
            foreach ($files as $file) {
                $content = file_get_contents(base_path() . $file);
                $content = str_replace('APP_VERSION="' . $current_version . '"', 'APP_VERSION="' . $next_version . '"', $content);

                file_put_contents(base_path() . $file, $content);
            }

            file_put_contents(app_path() . '/config/version.php', "<?php\n\nreturn '{$next_version}';");

            echo "\033[32mUpgraded from {$current_version} to {$next_version}\033[39m\n";
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
