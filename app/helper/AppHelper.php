<?php 

/**
 * @param $name
 * @param $fallback
 * @return mixed
 */
function app($name, $fallback = null)
{
    $result = AppModel::query($name);
    if ($result === null) {
        return $fallback;
    }

    return $result;
}

/**
 * @return void
 */
function app_mail_config()
{
    $_ENV['SMTP_FROMNAME'] = app('smtp_fromname');
    $_ENV['SMTP_FROMADDRESS'] = app('smtp_fromaddress');
    $_ENV['SMTP_HOST'] = app('smtp_host');
    $_ENV['SMTP_PORT'] = app('smtp_port');
    $_ENV['SMTP_USERNAME'] = app('smtp_username');
    $_ENV['SMTP_PASSWORD'] = app('smtp_password');
    $_ENV['SMTP_ENCRYPTION'] = app('smtp_encryption');
}

/**
 * @return array
 */
function mail_properties()
{
    $result = [];

    if ($_ENV['SMTP_ENCRYPTION'] === 'none') {
        $_ENV['SMTP_ENCRYPTION'] = 'tls';

        $result = [
            'SMTPSecure' => false,
            'SMTPAutoTLS' => false
        ];
    }

    return $result;
}

/**
 * @return mixed
 */
function auth()
{
    return UserModel::getAuthUser();
}

/**
 * @return void
 */
function app_set_timezone()
{
    $timezone = app('timezone');
    if ((is_string($timezone)) && (strlen($timezone) > 0)) {
        date_default_timezone_set($timezone);
    }
}

/**
 * @return bool
 */
function plant_attr($name)
{
    try {
        return PlantDefAttrModel::isActive($name);
    } catch (\Exception $e) {
        return false;
    }
}