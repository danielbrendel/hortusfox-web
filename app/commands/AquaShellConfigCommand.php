<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class AquaShellConfigCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        $url_config = '# AquaShell URL config, auto-generated at ' . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
        $url_config .= 'const APP_SERVICE_URL string <= "' . env('APP_SERVICE_URL') . '";' . PHP_EOL;
        $url_config .= 'const APP_GITHUB_URL string <= "' . env('APP_GITHUB_URL') . '";' . PHP_EOL;
        $url_config .= 'const APP_GITHUB_SPONSOR string <= "' . env('APP_GITHUB_SPONSOR') . '";' . PHP_EOL;
        $url_config .= 'const APP_DONATION_KOFI string <= "' . env('APP_DONATION_KOFI') . '";' . PHP_EOL;
        $url_config .= 'const APP_SOCIAL_DISCORD string <= "' . env('APP_SOCIAL_DISCORD') . '";' . PHP_EOL;
        $url_config .= 'const APP_SOCIAL_BLUESKY string <= "' . env('APP_SOCIAL_BLUESKY') . '";' . PHP_EOL;
        file_put_contents(base_path() . '/scripts/tmp/urls.conf.dnys', $url_config);

        $url_config = '# AquaShell database config, auto-generated at ' . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;
        $url_config .= 'const DATABASE_HOST string <= "' . env('DB_HOST') . '";' . PHP_EOL;
        $url_config .= 'const DATABASE_PORT int <= ' . env('DB_PORT') . ';' . PHP_EOL;
        $url_config .= 'const DATABASE_NAME string <= "' . env('DB_DATABASE') . '";' . PHP_EOL;
        $url_config .= 'const DATABASE_USER string <= "' . env('DB_USER') . '";' . PHP_EOL;
        $url_config .= 'const DATABASE_PASS string <= "' . env('DB_PASSWORD') . '";' . PHP_EOL;
        file_put_contents(base_path() . '/scripts/tmp/db.conf.dnys', $url_config);

        echo "Successfully created AquaShell configuration files.\n";
    }
}
    