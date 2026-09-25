<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class MigrationMail implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        try {
            $current_mail = env('APP_CONTACT');
            $new_mail = $args->get(0)?->getValue(0);

            $checkvalid = filter_var($new_mail, FILTER_VALIDATE_EMAIL);
            if ($checkvalid === false) {
                throw new \Exception('Invalid address specified for new mailbox: ' . $new_mail);
            }

            $files = ['/.env', '/.env.example', '/.env.testing', '/SECURITY.md', '/public/install/index.php', '/scripts/install.dnys'];
            foreach ($files as $file) {
                $content = file_get_contents(base_path() . $file);
                $content = str_replace($current_mail, $new_mail, $content);

                file_put_contents(base_path() . $file, $content);
            }

            echo "\033[32mUpgraded from {$current_mail} to {$new_mail}\033[39m\n";
        } catch (\Exception $e) {
            echo "\033[31mOperation failed: {$e->getMessage()}\033[39m\n";
        }
    }
}
