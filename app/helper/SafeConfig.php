<?php

/**
 * @param $config
 * @param $fallback
 * @return mixed
 */
function safe_config($config, $fallback = null)
{
    if (file_exists(app_path() . '/config/' . $config . '.php')) {
        return config($config);
    } else {
        return $fallback;
    }
}