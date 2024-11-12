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
 * @param $ident
 * @param $default
 * @return mixed
 */
function url_param($ident, $default = '')
{
    if (isset($_GET[$ident])) {
        return $_GET[$ident];
    }

    return $default;
}

/**
 * @param $ident
 * @param $precedence
 * @param $default
 * @return mixed
 */
function url_query($ident, $precedence, $default = '')
{
    if (isset($_GET[$ident])) {
        return $precedence . $ident . '=' . $_GET[$ident];
    }

    return $default;
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

function convert_date($format, $timestamp = null)
{
    return UtilsModule::convertDate($format, $timestamp);
}

function daylight_saving_time()
{
    return UtilsModule::isDaylightSavingTime();
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

/**
 * @param $path
 * @return string
 */
function workspace_url($path)
{
    $rp = app('mail_rp_address', null);
    if ((is_string($rp)) && (strlen($rp) > 0)) {
        return $rp . $path;
    }

    return url($path);
}

/**
 * @param $asset
 * @return string
 */
function abs_photo($asset)
{
    return UtilsModule::absolutePhoto($asset);
}

/**
 * @return string
 */
function share_api_host()
{
    $host = app('custom_media_share_host');
    if ((is_string($host)) && (strlen($host) > 0)) {
        return $host;
    }

    return env('APP_SERVICE_URL');
}