<?php

/**
 * This class represents your module
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
    public static function createThumbFile($srcfile, $imgtype, $basefile, $fileext)
    {
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

        $dstimg = imagecreatetruecolor($newwidth, $newheight);
        if (!$dstimg)
            return false;
        
        $srcimage = null;
        switch ($imgtype) {
            case IMAGETYPE_PNG:
                $srcimage = imagecreatefrompng($srcfile);
                imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                static::correctImageRotation($srcfile, $dstimg);
                imagepng($dstimg, $basefile . "_thumb." . $fileext);
                break;
            case IMAGETYPE_JPEG:
                $srcimage = imagecreatefromjpeg($srcfile);
                imagecopyresampled($dstimg, $srcimage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
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
                $result[] = $file;
            }
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
     * @param $workspace
     * @param $lang
     * @param $scroller
     * @param $enablechat
     * @param $onlinetimelimit
     * @param $chatonlineusers
     * @param $chattypingindicator
     * @param $cronpw
     * @return void
     */
    public static function saveEnvironment($workspace, $lang, $scroller, $enablechat, $onlinetimelimit, $chatonlineusers, $chattypingindicator, $cronpw)
    {
        $new_env_settings = [
            'APP_WORKSPACE' => $workspace,
            'APP_LANG' => $lang,
            'APP_ENABLESCROLLER' => $scroller,
            'APP_ENABLECHAT' => $enablechat,
            'APP_ONLINEMINUTELIMIT' => $onlinetimelimit,
            'APP_SHOWCHATONLINEUSERS' => $chatonlineusers,
            'APP_SHOWCHATTYPINGINDICATOR' => $chattypingindicator,
            'APP_CRONPW' => $cronpw
        ];

        foreach ($new_env_settings as $key => $value) {
            if (isset($_ENV[$key])) {
                $_ENV[$key] = $value;
            }
        }

        $env_content = "# Automatically generated at " . date('Y-m-d H:i:s') . "\n";

        foreach ($_ENV as $key => $value) {
            if (gettype($value) === 'boolean') {
                $env_content .= $key . '=' . (($value) ? "true" : "false");
            } else if (gettype($value) === 'double') {
                $env_content .= $key . '=' . $value;
            } else if (gettype($value) === 'integer') {
                $env_content .= $key . '=' . $value;
            } else if (gettype($value) === 'string') {
                $env_content .= $key . '="' . $value . '"';
            } else if ($value === null) {
                $env_content .= $key . '=null';
            } else {
                $env_content .= $key . '=' . $value;
            }

            $env_content .= "\n";
        }

        file_put_contents(base_path() . '/.env', $env_content);
    }
}
