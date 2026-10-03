<?php

/**
 * Class AppModel
 * 
 * Management of app environment settings
 */ 
class AppModel extends \Asatru\Database\Model {
    static $allowed_attributes = [
        'id',
        'workspace',
        'language',
        'timezone',
        'scroller',
        'quick_add',
        'tasks_enable',
        'inventory_enable',
        'calendar_enable',
        'chat_enable',
        'chat_timelimit',
        'chat_showusers',
        'chat_indicator',
        'chat_system',
        'history_enable',
        'history_name',
        'enable_media_share',
        'custom_media_share_host',
        'cronjob_pw',
        'custom_head_code',
        'overlay_alpha',
        'smtp_enable_auth',
        'smtp_fromname',
        'smtp_fromaddress',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'mail_rp_address',
        'pwa_enable',
        'owm_enable',
        'owm_api_key',
        'owm_latitude',
        'owm_longitude',
        'owm_unittype',
        'owm_cache',
        'plantrec_enable',
        'plantrec_apikey',
        'plantrec_quickscan',
        'allow_custom_attributes',
        'system_message_plant_log',
        'auto_backup',
        'backup_path',
        'auth_proxy_enable',
        'auth_proxy_header_email',
        'auth_proxy_header_username',
        'auth_proxy_sign_up',
        'auth_proxy_whitelist',
        'auth_proxy_hide_logout',
        'created_at'
    ];

    /**
     * @param $attribute
     * @return void
     * @throws \Exception
     */
    public static function validateAttribute($attribute)
    {
        if (!in_array($attribute, static::$allowed_attributes)) {
            throw new \Exception('Invalid attribute specified: ' . $attribute);
        }
    }

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
            static::validateAttribute($name);
            
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
                'display' => 'standalone',
                'background_color' => '#323232',
                'theme_color' => '#323232'
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
            'none' => 'N/A',
            'tls' => 'STARTTLS',
            'smtps' => 'SMTPS'
        ];
    }
}