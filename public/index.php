<?php

/*
    Asatru PHP (dnyAsatruPHP) developed by Daniel Brendel
    
    (C) 2019 - 2024 by Daniel Brendel
    
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/
    
    Released under the MIT license
*/

//If composer is installed then utilize its autoloader
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

//For https when behind a reverse proxy
if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $_SERVER['HTTPS'] = 'on';
}

//Include the framework bootstrap script in order to process the application
require_once __DIR__ . '/../vendor/danielbrendel/asatru-php-framework/src/bootstrap.php';
