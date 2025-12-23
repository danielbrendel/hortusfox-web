<?php

/*
    Asatru PHP - Command handler
*/

/**
 * Command handler class
 */
class ThemeInstallCommand implements Asatru\Commands\Command {
    /**
     * Command handler method
     * 
     * @param $args
     * @return void
     */
    public function handle($args)
    {
        if ($args->count() < 1) {
            echo "Invalid argument: please provide the name of the theme to install";
            return;
        }

        $theme = $args->get(0)->getValue(0);

        echo "Attempting to install {$theme}...\n";

        if (is_dir(public_path() . '/themes/' . $theme)) {
            echo "Theme {$theme} is already installed.\n";
            return;
        }

        echo "Downloading '" . env('APP_SERVICE_URL') . "/downloads/" . $theme . ".zip'...\n";

        if (!UtilsModule::downloadFile(env('APP_SERVICE_URL') . '/downloads/' . $theme . '.zip', public_path() . '/themes/' . $theme . '.zip')) {
            echo "Error: failed to download theme package\n";
            return;
        }

        echo "Extracting archive...\n";

        $zip = new ZipArchive();

        if (!$zip->open(public_path() . '/themes/' . $theme . '.zip')) {
            echo "Error: failed to extract theme package\n";
            return;
        }

        $zip->extractTo(public_path() . '/themes');
        $zip->close();

        unlink(public_path() . '/themes/' . $theme . '.zip');

        echo "Done.\n";
    }
}
    