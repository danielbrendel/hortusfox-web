<?php

/*
    Asatru PHP (dnyAsatruPHP) developed by Daniel Brendel
    
    (C) 2019 - 2023 by Daniel Brendel
    
    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/
    
    Released under the MIT license
*/

//Set application root directory path
define('ASATRU_APP_ROOT', __DIR__ . '/../..');

//If composer is installed we utilize its autoloader
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

//Fetch constants
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/constants.php';

//Require the controller component
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/controller.php';

//Require logging
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/logger.php';

//Require .env config management
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/dotenv.php';

//Require autoload component
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/autoload.php';

//Require helpers
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/helper.php';

//Require mail wrapper
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/mailwrapper.php';

//Require testing component
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/testing.php';

//Parse .env file if it exists
if (file_exists(__DIR__ . '/../../.env.testing')) {
    env_parse(__DIR__ . '/../../.env.testing');
}

//Enable debug mode error handling
$_ENV['APP_DEBUG'] = true;
error_reporting(E_ALL);


//Check if we shall create/continue a session
if ((isset($_ENV['APP_SESSION'])) && ($_ENV['APP_SESSION'])) {
    if (!session_start()) {
        throw new Exception('Failed to create/continue the session');
    }

    if (!isset($_SESSION['continued'])) { //Check if a new session
        //Create CSRF-token
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        //Mark session
        $_SESSION['continued'] = true;
    }
}

//Require localization
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/locale.php';


//Require database management
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/database.php';

//Require event manager
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/events.php';

//Perform autoloading
$auto = new Asatru\Autoload\Autoloader(__DIR__ . '/../config/autoload.php');
$auto->load();

//Load validators if any
Asatru\Controller\CustomPostValidators::load(__DIR__ . '/../validators');
