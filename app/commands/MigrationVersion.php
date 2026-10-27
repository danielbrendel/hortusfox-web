<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class MigrationVersion implements Asatru\Commands\Command {
    /**
     * @var array
     */
    private $files = ['.env', '.env.example', '.env.testing', 'public/install/index.php', 'scripts/install.dnys'];

    /**
     * @param $message
     * @param $push
     * @return void
     */
    private function commit($message, $push = false)
    {
        $list = implode(' ', $this->files);
        $target_branch = 'main';
        $current_branch = shell_exec("git branch --show-current");
        
        system("git switch {$target_branch}");
        system("git add {$list}");
        system("git add app/config/version.php");
        system("git commit -m \"{$message}\"");

        if ($push) {
            system("git push");
        }

        system("git switch {$current_branch}");
    }

    /**
     * @param $message
     * @return void
     */
    private function publish($message)
    {
        $this->commit($message, true);
    }

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
            $option = $args?->get(0)?->getValue(0);

            if (strpos($current_version, '.') === false) {
                $current_version = $current_version . '.0';
            }

            if (strpos($next_version, '.') === false) {
                $next_version = $next_version . '.0';
            }

            foreach ($this->files as $file) {
                $content = file_get_contents(base_path() . '/' . $file);
                $content = str_replace('APP_VERSION="' . $current_version . '"', 'APP_VERSION="' . $next_version . '"', $content);

                file_put_contents(base_path() . '/' . $file, $content);
            }

            file_put_contents(app_path() . '/config/version.php', "<?php\n\nreturn '{$next_version}';");

            if (is_string($option)) {
                if ($option === '--commit') {
                    $this->commit("Upgrade from {$current_version} to {$next_version}");
                } else if ($option === '--publish') {
                    $this->publish("Upgrade from {$current_version} to {$next_version}");
                } else {
                    echo "\033[93mUnknown option '{$option}', ignoring parameter.\033[39m\n";
                }
            }

            echo "\033[32mUpgraded from {$current_version} to {$next_version}\033[39m\n";
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
