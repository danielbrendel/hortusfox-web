<?php

/**
 * Class UtilsModule
 * 
 * A collection of various useful utility methods
 */
class UtilsModule {
    const TYPING_SECONDS = 5;

    /**
     * @param $lang
     * @return void
     */
    public static function setLanguage($lang)
    {
        setLanguage($lang);
        $_COOKIE['current_language'] = $lang;
    }

    /**
     * @return string
     */
    public static function getLanguage()
    {
        return $_COOKIE['current_language'];
    }

    /**
     * @return array
     */
    public static function GetMonthList()
    {
        return [
            __('app.january'),
            __('app.february'),
            __('app.march'),
            __('app.april'),
            __('app.may'),
            __('app.june'),
            __('app.july'),
            __('app.august'),
            __('app.september'),
            __('app.october'),
            __('app.november'),
            __('app.december'),
        ];
    }

    /**
     * @param $file
     * @return mixed|null
     */
    public static function getImageExt($file)
    {
        $imagetypes = [
            'png' => IMAGETYPE_PNG,
            'jpg' => IMAGETYPE_JPEG,
            'gif' => IMAGETYPE_GIF
        ];

        foreach ($imagetypes as $ext => $type) {
            if (exif_imagetype($file) === $type) {
                return $ext;
            }
        }

        return null;
    }

    /**
     * @param string $imgFile
     * @return bool
     */
    public static function isValidImage($imgFile)
    {
        $imagetypes = array(
            IMAGETYPE_PNG,
            IMAGETYPE_JPEG,
            IMAGETYPE_GIF
        );

        if (!file_exists($imgFile)) {
            return false;
        }

        foreach ($imagetypes as $type) {
            if (exif_imagetype($imgFile) === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $ext
     * @param $file
     * @return mixed|null
     */
    public static function getImageType($ext, $file)
    {
        $imagetypes = array(
            array('png', IMAGETYPE_PNG),
            array('jpg', IMAGETYPE_JPEG),
            array('jpeg', IMAGETYPE_JPEG),
            array('gif', IMAGETYPE_GIF)
        );

        for ($i = 0; $i < count($imagetypes); $i++) {
            if (strtolower($ext) == $imagetypes[$i][0]) {
                if (exif_imagetype($file . '.' . $ext) == $imagetypes[$i][1])
                    return $imagetypes[$i][1];
            }
        }

        return null;
    }

    /**
     * @param $filename
     * @param &$image
     * @return void
     */
    private static function correctImageRotation($filename, &$image)
    {
        $exif = @exif_read_data($filename);

        if (!isset($exif['Orientation']))
            return;

        switch($exif['Orientation'])
        {
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, 270, 0);
                break;
            default:
                break;
        }
    }

    /**
     * @param $srcfile
     * @param $imgtype
     * @param $basefile
     * @param $fileext
     * @return bool
     */
    public static function createThumbFile($srcfile, $imgtype, $basefile, $fileext, $setmem = true)
    {
        if ($setmem === true) {
            ini_set('memory_limit', '512M');
        }

        list($width, $height) = getimagesize($srcfile);

        $factor = config('resize')->default;

        if ($width > $height) {
            if (($width >= 800) and ($width < 1000)) {
                $factor = config('resize')->factor_1;
            } else if (($width >= 1000) and ($width < 1250)) {
                $factor = config('resize')->factor_2;
            } else if (($width >= 1250) and ($width < 1500)) {
                $factor = config('resize')->factor_3;
            } else if (($width >= 1500) and ($width < 2000)) {
                $factor = config('resize')->factor_4;
            } else if ($width >= 2000) {
                $factor = config('resize')->factor_5;
            }
        } else {
            if (($height >= 800) and ($height < 1000)) {
                $factor = config('resize')->factor_1;
            } else if (($height >= 1000) and ($height < 1250)) {
                $factor = config('resize')->factor_2;
            } else if (($height >= 1250) and ($height < 1500)) {
                $factor = config('resize')->factor_3;
            } else if (($height >= 1500) and ($height < 2000)) {
                $factor = config('resize')->factor_4;
            } else if ($height >= 2000) {
                $factor = config('resize')->factor_5;
            }
        }

        $newwidth = $factor * $width;
        $newheight = $factor * $height;

        $dstimg = @imagecreatetruecolor($newwidth, $newheight);
        if (!$dstimg)
            return false;
        
        $srcimage = null;
        switch ($imgtype) {
            case IMAGETYPE_PNG:
                $srcimage = imagecreatefrompng($srcfile);
                @imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                static::correctImageRotation($srcfile, $dstimg);
                imagepng($dstimg, $basefile . "_thumb." . $fileext);
                break;
            case IMAGETYPE_JPEG:
                $srcimage = imagecreatefromjpeg($srcfile);
                @imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                static::correctImageRotation($srcfile, $dstimg);
                imagejpeg($dstimg, $basefile . "_thumb." . $fileext);
                break;
            case IMAGETYPE_GIF:
                copy($srcfile, $basefile . "_thumb." . $fileext);
                break;
            default:
                return false;
                break;
        }

        return true;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getStats()
    {
        try {
            return [
                'users' => UserModel::getCount(),
                'locations' => LocationsModel::getCount(),
                'plants' => PlantsModel::getCount(),
                'tasks' => TasksModel::getOpenTaskCount()
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public static function getLanguageList()
    {
        $result = [];

        $files = scandir(app_path('/lang'));
        foreach ($files as $file) {
            if (substr($file, 0, 1) !== '.') {
                $result[] = [
                    'ident' => $file, 
                    'name' => locale_get_display_language($file, static::getLanguage()),
                    'region' => locale_get_display_region($file, static::getLanguage())
                ];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getLabeledLanguageList()
    {
        $result = [];

        $list = static::getLanguageList();
        $duplicates = [];

        foreach ($list as $item) {
            if (isset($duplicates[$item['name']])) {
                $duplicates[$item['name']]++;
            } else {
                $duplicates[$item['name']] = 0;
            }
        }
        
        foreach ($list as $item) {
            $entry = $item;

            if ($duplicates[$item['name']] > 0) {
                $entry['label'] = $item['name'] . ' (' . $item['region'] . ')';
            } else {
                $entry['label'] = $item['name'];
            }

            $result[] = $entry;
        }

        return $result;
    }

    /**
     * @return bool
     */
    public static function isTyping($dt)
    {
        return ($dt !== null) && (Carbon::parse($dt)->diffInSeconds() <= self::TYPING_SECONDS);
    }

    /**
     * @param $folder
     * @return void
     * @throws \Exception
     */
    public static function clearFolder($folder)
    {
        try {
            if (!is_dir($folder)) {
                throw new \Exception('Invalid folder: ' . $folder);
            }

            if (substr($folder, strlen($folder) - 1, 1) !== DIRECTORY_SEPARATOR) {
                $folder .= DIRECTORY_SEPARATOR;
            }

            $items = glob($folder . '*', GLOB_MARK);
            foreach ($items as $key => $value) {
                if (is_dir($value)) {
                    static::clearFolder($value);
                } else {
                    unlink($value);
                }
            }
            
            rmdir($folder);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $text
     * @return string
     * @throws \Exception
     */
    public static function translateURLs($text)
    {
        try {
            return preg_replace('/\bhttps?:\/\/[^\s]+/i', '<a href="$0">$0</a>', $text);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $code
     * @return string
     * @throws \Exception
     */
    public static function convertRgbToHex($code)
    {
        try {
            if (strpos($code, 'rgb(') === false) {
                return $code;
            }

            $start = strpos($code, '(');
            $end = strpos($code, ')');

            if (($start !== false) && ($end !== false)) {
                $expr = str_replace(' ', '', substr($code, $start + 1, $end - 1));

                $tokens = explode(',', $expr);

                $result = '#';

                foreach ($tokens as $token) {
                    $result .= dechex(intval($token));
                }

                return $result;
            }

            return $code;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $content
     * @param $type
     * @return string
     * @throws \Exception
     */
    public static function readablePlantAttribute($content, $type)
    {
        try {
            if (!$content) {
                return 'N/A';
            }

            if ($type === 'name') {
                return $content;
            } else if (($type === 'last_watered') || ($type === 'last_repotted') || ($type === 'last_fertilised') || ($type === 'date_of_purchase')) {
                return date('Y-m-d', strtotime($content));
            } else if (($type === 'health_state') || ($type === 'lifespan')) {
                return __('app.' . $content);
            } else if ($type === 'hardy') {
                if (is_null($content)) {
                    return 'N/A';
                } else {
                    return ($content) ? __('app.yes') : __('app.no');
                }
            } else if ($type === 'light_level') {
                return __('app.' . $content);
            } else if ($type === 'humidity') {
                return $content . '%';
            } else if ($type === 'cutting_month') {
                return UtilsModule::getMonthList()[$content];
            } else {
                return $content;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $needle
     * @param $haystack
     * @param $prop
     * @param $tolower
     * @return bool
     */
    public static function in_array_stdclass($needle, $haystack, $prop, $tolower = true)
    {
        foreach ($haystack as $item) {
            if (!is_object($item)) {
                $item = (object)$item;
            }

            if ($tolower) {
                if (strtolower($item->$prop) === strtolower($needle)) {
                    return true;
                }
            } else {
                if ($item->$prop === $needle) {
                    return true;
                }
            }
            
        }

        return false;
    }

    /**
     * @param $asset
     * @return string
     */
    public static function absolutePhoto($asset)
    {
        if ((strpos($asset, 'http://') === 0) || (strpos($asset, 'https://') === 0)) {
            return $asset;
        }

        if (!file_exists(public_path() . '/img/' . $asset)) {
            return asset('img/' . PlantsModel::PLANT_PLACEHOLDER_FILE);
        }

        return asset('img/' . $asset);
    }

    /**
     * @param $haystack
     * @param $key
     * @param $value
     * @return int
     */
    public static function array_from_key_value($haystack, $key, $value)
    {
        foreach ($haystack as $ident => $item) {
            if ($item[$key] == $value) {
                return $ident;
            }
        }

        return -1;
    }

    /**
     * @return bool
     */
    public static function isHTTPS()
    {
        return (isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] !== 'off');
    }

    /**
     * @param $ident
     * @param $dest
     * @return string
     * @throws \Exception
     */
    public static function uploadFile($ident, $dest)
    {
        if ((!isset($_FILES[$ident])) || ($_FILES[$ident]['error'] !== UPLOAD_ERR_OK)) {
            throw new \Exception('Errorneous file');
        }

        $file_ext = UtilsModule::getImageExt($_FILES[$ident]['tmp_name']);

        if ($file_ext === null) {
            throw new \Exception('File is not a valid image');
        }

        $file_name = md5(random_bytes(55) . date('Y-m-d H:i:s'));

        move_uploaded_file($_FILES[$ident]['tmp_name'], $dest . $file_name . '.' . $file_ext);

        return $dest . $file_name . '.' . $file_ext;
    }

    /**
     * @param $format
     * @param $timestamp
     * @return string
     */
    public static function convertDate($format, $timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time();
        }
        
        $date = new DateTime("@$timestamp", new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone(app('timezone', date_default_timezone_get())));
        
        return $date->format($format);
    }

    /**
     * @return bool
     */
    public static function isDaylightSavingTime()
    {
        $date = new DateTime("now", new DateTimeZone(app('timezone', date_default_timezone_get())));

        return (bool)$date->format('I');
    }

    /**
     * @param $expression
     * @return array
     */
    public static function splitTags($expression)
    {
        if (!is_string($expression)) {
            return [];
        }

        return preg_split('/\s+/', trim($expression));
    }

    /**
     * @param $html
     * @return string
     */
    public static function purify($html)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', base_path() . '/cache');

        $purifier = new HTMLPurifier($config);
        
        return $purifier->purify($html);
    }

    /**
     * @param $icon
     * @return string
     */
    public static function iconAsset($icon)
    {
        if ((is_string($icon)) && (file_exists(public_path() . '/img/' . $icon))) {
            return asset('img/' . $icon);
        }

        return asset('img/nolocicon.jpg');
    }
}
