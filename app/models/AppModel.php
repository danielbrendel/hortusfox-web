<?php

/**
 * This class extends the base model class and represents your associated table
 */ 
class AppModel extends \Asatru\Database\Model {
    /**
     * @param $name
     * @param $fallback
     * @param $profile
     * @return mixed
     * @throws \Exception
     */
    public static function query($name, $fallback = null, $profile = 1)
    {
        try {
            $item = static::raw('SELECT * FROM `@THIS` WHERE id = ?', [$profile])->first();
            if (!$item) {
                return $fallback;
            }

            return $item->get($name);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $name
     * @param $value
     * @return void
     * @throws \Exception
     */
    public static function updateSingle($name, $value)
    {
        try {
            static::raw('UPDATE `@THIS` SET ' . $name . ' = ?', [$value]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $set
     * @return void
     * @throws \Exception
     */
    public static function updateSet($set)
    {
        try {
            foreach ($set as $key => $value) {
                static::raw('UPDATE `@THIS` SET ' . $key . ' = ?', [$value]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateCronjobToken()
    {
        try {
            $token = md5(random_bytes(55) . date('Y-m-d H:i:s'));

            static::updateSingle('cronjob_pw', $token);

            return $token;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $workspace
     * @return void
     * @throws \Exception
     */
    public static function writeManifest($workspace)
    {
        try {
            $manifest = [
                'name' => $workspace,
                'short_name' => $workspace,
                'icons' => [
                    [
                        'src' => '/logo.png',
                        'sizes' => '256x256',
                        'type' => 'image/png'
                    ]
                ],
                'start_url' => '/',
                'display' => 'fullscreen',
                'background_color' => 'white',
                'theme_color' => 'white'
            ];

            file_put_contents(public_path() . '/manifest.json', json_encode($manifest));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public static function getMailEncryptionTypes()
    {
        return [
            'none' => 'none',
            'TLS' => 'tls',
            'SSL' => 'smtps'
        ];
    }

    /**
     * Return the associated table name of the migration
     * 
     * @return string
     */
    public static function tableName()
    {
        return 'AppModel';
    }
}