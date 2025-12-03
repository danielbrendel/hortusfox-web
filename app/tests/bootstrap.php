<?php

/*
    Asatru PHP (dnyAsatruPHP) developed by Daniel Brendel
    
    (C) 2019 - 2025 by Daniel Brendel
    
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/
    
    Released under the MIT license
*/

//If composer is installed we utilize its autoloader
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

//Set application root directory path
define('ASATRU_APP_ROOT', __DIR__ . '/../..');

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

//Require config component
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/config.php';

//Require helpers
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/helper.php';

//Require Html helper
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/html.php';

//Require form helper
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/forms.php';

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
if ((isset($_ENV['SESSION_ENABLE'])) && ($_ENV['SESSION_ENABLE'])) {
    if ((isset($_ENV['SESSION_DURATION'])) && ($_ENV['SESSION_DURATION'] !== null) && (is_numeric($_ENV['SESSION_DURATION']))) {
        ini_set('session.cookie_lifetime', $_ENV['SESSION_DURATION']);
        ini_set('session.gc_maxlifetime', $_ENV['SESSION_DURATION']);
    }

    if ((isset($_ENV['SESSION_NAME'])) && (is_string($_ENV['SESSION_NAME'])) && (strlen($_ENV['SESSION_NAME']) > 0)) {
        session_name($_ENV['SESSION_NAME']);
    }

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

//Require modules
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/modules.php';

//Require event manager
require_once __DIR__ . '/../../vendor/danielbrendel/asatru-php-framework/src/events.php';

//Perform autoloading
$auto = new Asatru\Autoload\Autoloader(__DIR__ . '/../config/autoload.php');
$auto->load();

//Load validators if any
Asatru\Controller\CustomPostValidators::load(__DIR__ . '/../validators');
        