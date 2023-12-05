<?php

/*
    Asatru PHP (dnyAsatruPHP) developed by Daniel Brendel
    
    (C) 2019 - 2023 by Daniel Brendel
    
    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/
    
    Released under the MIT license
*/

//If composer is installed then utilize its autoloader
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

//Set data for a long-term session
$session_cookie_duration = 60 * 60 * 24 * 365;
ini_set('session.cookie_lifetime', $session_cookie_duration);
ini_set('session.gc_maxlifetime', $session_cookie_duration);

//Include the framework bootstrap script in order to process the application
require_once __DIR__ . '/../vendor/danielbrendel/asatru-php-framework/src/bootstrap.php';
