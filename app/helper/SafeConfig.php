<?php

/**
 * @param $config
 * @return mixed
 */
function safe_config($config)
{
    if (file_exists(app_path() . '/config/' . $config . '.php')) {
        return config($config);
    } else {
        return 1;
    }
}