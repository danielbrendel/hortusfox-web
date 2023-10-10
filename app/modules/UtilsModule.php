<?php

/**
 * This class represents your module
 */
class UtilsModule {
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
}
