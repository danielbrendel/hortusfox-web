<?php

/**
 * Class ThemeModule
 * 
 * Manages themes of the workspace
 */
class ThemeModule {
    /** @var array $theme_data */
    public static $theme_data = null;

    /**
     * @param $path
     * @return void
     * @throws \Exception
     */
    public static function load($path)
    {
        try {
            self::$theme_data = null;

            if (!file_exists($path . '/theme.json')) {
                throw new \Exception('Theme definition file not found: ' . $path . '/theme.json');
            }

            self::$theme_data = json_decode(file_get_contents($path . '/theme.json'));
            if (!is_object(self::$theme_data)) {
                throw new \Exception('Invalid data @ ' . $path . '/theme.json: ' . print_r(self::$theme_data, true));
            }

            if ((!isset(self::$theme_data->author)) || (!isset(self::$theme_data->contact)) || (!isset(self::$theme_data->version))) {
                throw new \Exception('Missing author, contact or version data');
            }
            
            if (!file_exists($path . '/' . self::$theme_data->banner)) {
                throw new \Exception('Banner asset not found');
            }
            
            if ((isset(self::$theme_data->include)) && (file_exists(public_path() . '/themes/' . self::$theme_data->name . '/' . self::$theme_data->include))) {
                self::$theme_data->include = asset('themes/' . self::$theme_data->name . '/' . self::$theme_data->include);
            } else {
                self::$theme_data->include = null;
            }

            if ((isset(self::$theme_data->script)) && (file_exists(public_path() . '/themes/' . self::$theme_data->name . '/' . self::$theme_data->script))) {
                self::$theme_data->script = asset('themes/' . self::$theme_data->name . '/' . self::$theme_data->script);
            } else {
                self::$theme_data->script = null;
            }

            self::$theme_data->banner_url = asset('themes/' . self::$theme_data->name . '/' . self::$theme_data->banner);

            self::$theme_data->inline_rules = '';
            foreach (self::$theme_data->rules as $key => $value) {
                self::$theme_data->inline_rules .= $key . ': ' . $value . ' !important;';
            }

            if (isset(self::$theme_data->icon)) {
                if (!file_exists($path . '/' . self::$theme_data->icon->asset)) {
                    throw new \Exception('Icon asset not found');
                }

                self::$theme_data->icon->url = asset('themes/' . self::$theme_data->name . '/' . self::$theme_data->icon->asset);

                self::$theme_data->icon->inline_rules = new \stdClass();

                self::$theme_data->icon->inline_rules->base = '';
                foreach (self::$theme_data->icon->base as $key => $value) {
                    self::$theme_data->icon->inline_rules->base .= $key . ': ' . $value . ' !important;';
                }
                
                self::$theme_data->icon->inline_rules->img = '';
                foreach (self::$theme_data->icon->img as $key => $value) {
                    self::$theme_data->icon->inline_rules->img .= $key . ': ' . $value . ' !important;';
                }
            }

            if (isset(self::$theme_data->accessory)) {
                if (!file_exists($path . '/' . self::$theme_data->accessory->asset)) {
                    throw new \Exception('Icon asset not found');
                }

                self::$theme_data->accessory->url = asset('themes/' . self::$theme_data->name . '/' . self::$theme_data->accessory->asset);

                self::$theme_data->accessory->inline_rules = new \stdClass();

                self::$theme_data->accessory->inline_rules->base = '';
                foreach (self::$theme_data->accessory->base as $key => $value) {
                    self::$theme_data->accessory->inline_rules->base .= $key . ': ' . $value . ' !important;';
                }
                
                self::$theme_data->accessory->inline_rules->img = '';
                foreach (self::$theme_data->accessory->img as $key => $value) {
                    self::$theme_data->accessory->inline_rules->img .= $key . ': ' . $value . ' !important;';
                }
            }

            if (isset(self::$theme_data->mail)) {
                if (!file_exists($path . '/' . self::$theme_data->mail)) {
                    throw new \Exception('Mail style asset not found');
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function data()
    {
        try {
            return self::$theme_data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return bool
     */
    public static function ready()
    {
        return (is_object(self::$theme_data));
    }

    /**
     * @return bool
     */
    public static function hasMailStyles()
    {
        return (static::ready()) && (isset(self::$theme_data->mail)) && (file_exists(public_path() . '/themes/' . self::$theme_data->name . '/' . self::$theme_data->mail));
    }

    /**
     * @return string
     */
    public static function getMailStyles()
    {
        return file_get_contents(public_path() . '/themes/' . self::$theme_data->name . '/' . self::$theme_data->mail);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function list()
    {
        try {
            $result = [];

            $folders = scandir(public_path() . '/themes');
            foreach ($folders as $folder) {
                if (((substr($folder, 0, 1)) !== '.') && (is_dir(public_path() . '/themes/' . $folder))) {
                    $result[] = $folder;
                }
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function startImport()
    {
        try {
            if ((!isset($_FILES['theme'])) || ($_FILES['theme']['error'] !== UPLOAD_ERR_OK) || (strpos($_FILES['theme']['type'], 'zip') === false)) {
                throw new \Exception('Failed to upload file or invalid file uploaded');
            }

            $result = [];

            $import_file = 'theme_import_' . date('Y-m-d_H-i-s');

            move_uploaded_file($_FILES['theme']['tmp_name'], public_path() . '/themes/' . $import_file . '.zip');

            $zip = new ZipArchive();

            if ($zip->open(public_path() . '/themes/' . $import_file . '.zip')) {
                $zip->extractTo(public_path() . '/themes/' . $import_file);
                $zip->close();

                $folders = scandir(public_path() . '/themes/' . $import_file);
                foreach ($folders as $folder) {
                    if (substr($folder, 0, 1) !== '.') {
                        if (!is_dir(public_path() . '/themes/' . $folder)) {
                            rename(public_path() . '/themes/' . $import_file . '/' . $folder, public_path() . '/themes/' . $folder);

                            static::load(public_path() . '/themes/' . $folder);
                            if (!static::ready()) {
                                throw new \Exception('Failed to load theme: ' . $folder);
                            }

                            LogModel::addLog(auth()->get('id'), 'themes', 'import_theme', self::$theme_data->name);

                            $result[] = $folder;
                        }
                    }
                }

                UtilsModule::clearFolder(public_path() . '/themes/' . $import_file);
            }

            unlink(public_path() . '/themes/' . $import_file . '.zip');

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getList()
    {
        try {
            $result = [];

            $orig = self::$theme_data;

            try {
                $folders = scandir(public_path() . '/themes');
                foreach ($folders as $folder) {
                    if (substr($folder, 0, 1) !== '.') {
                        if (is_dir(public_path() . '/themes/' . $folder)) {
                            self::load(public_path() . '/themes/' . $folder);

                            $result[] = self::data();
                        }
                    }
                }
            } catch (\Exception $e) {
                self::$theme_data = $orig;
            }

            self::$theme_data = $orig;

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get CSS variables for theme customization
     * @return array
     */
    public static function getCssVariables()
    {
        if (!self::ready()) {
            return [];
        }

        $variables = [];
        
        // Add theme-specific CSS variables if defined
        if (isset(self::$theme_data->css_variables)) {
            foreach (self::$theme_data->css_variables as $key => $value) {
                $variables[$key] = $value;
            }
        }

        return $variables;
    }

    /**
     * Generate inline CSS for theme variables
     * @return string
     */
    public static function getCssVariablesInline()
    {
        $variables = self::getCssVariables();
        
        if (empty($variables)) {
            return '';
        }

        $css = '<style id="theme-variables">:root {';
        foreach ($variables as $key => $value) {
            $css .= '--' . $key . ': ' . $value . ';';
        }
        $css .= '}</style>';

        return $css;
    }

    /**
     * Check if theme supports light/dark mode
     * @return bool
     */
    public static function supportsLightDarkMode()
    {
        if (!self::ready()) {
            return false;
        }

        return isset(self::$theme_data->supports_light_dark_mode) && 
               self::$theme_data->supports_light_dark_mode === true;
    }

    /**
     * @param $theme
     * @return void
     * @throws \Exception
     */
    public static function removeTheme($theme)
    {
        try {
            if (!is_dir(public_path() . '/themes/' . $theme)) {
                throw new \Exception('Invalid theme directory: ' . $theme);
            }

            UtilsModule::clearFolder(public_path() . '/themes/' . $theme);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
