<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class InfoCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        $current_version = config('version');

        try {
            $new_version = VersionModule::getCachedVersion();
        } catch (\Exception $e) {
            $new_version = $current_version;
        }
        
        echo "\033[96mHortusFox \033[32mv" . $current_version . "\033[39m " . (env('APP_DEBUG') ? 'Debug' : 'Release') . "\033[39m\n";
        echo "\033[95mDeveloped by " . env('APP_AUTHOR') . " (" . env('APP_CONTACT') . ")\033[39m\n\n";
        echo "\033[93mPHP Version:\033[39m \033[92m" . phpversion() . "\033[39m\n";
        echo "\033[93mMySQL Version:\033[39m \033[92m" . VersionModel::getSqlVersion() . "\033[39m\n";
        echo "\033[93mServer:\033[39m \033[92m" . php_uname('s') . " " . php_uname('v') . " " . php_uname('m') . "\033[39m\n";
        echo "\033[93mTimezone:\033[39m \033[92m" . date('Y-m-d H:i') . " (" . date_default_timezone_get() . ")\033[39m\n\n";
        echo "\033[93mGitHub URL:\033[39m \033[92m" . env('APP_GITHUB_URL') . "\033[39m\n";
        echo "\033[93mService URL:\033[39m \033[92m" . env('APP_SERVICE_URL') . "\033[39m\n";
        echo "\033[93mSponsoring URL:\033[39m \033[92m" . env('APP_GITHUB_SPONSOR') . "\033[39m\n";
        echo "\033[93mDonation URL:\033[39m \033[92m" . env('APP_DONATION_KOFI') . "\033[39m\n";

        if ($new_version > $current_version) {
            echo "\n\033[34mNew version available:\033[39m \033[92m" . env('APP_GITHUB_URL') . "/releases/tag/v" . $new_version . "\033[39m\n";
        }

        echo "\n";
    }
}
    