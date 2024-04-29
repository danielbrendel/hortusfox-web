<?php 
    if (!file_exists(__DIR__ . '/../../do_install')) {
        exit('Install indicator file not found');
    }

    const APP_GITHUB_URL = 'https://github.com/danielbrendel/hortusfox-web';
    const APP_SERVICE_URL = 'https://www.hortusfox.com';
    const APP_SPONSOR_BUTTON = true;

    const QUOTE_LIST = [
        'Be-Leaf in yourself!',
        'Yes, I really do need all these plants!',
        'Plants are like sunshine to your soul',
        'Bloom with grace',
        'You can never have too many plants'
    ];
?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'GET') { ?>
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

            <title>HortusFox - Installation</title>

            <link rel="stylesheet" type="text/css" href="/../css/bulma.css"/>
            <link rel="stylesheet" type="text/css" href="/install/install.css"/>

            <script src="/../js/fontawesome.js"></script>
            <script src="/install/install.js"></script>
        </head>

        <body>
            <div id="app">
                <div class="header">
                    <img src="/../logo.png" alt="logo"/>

                    <h1>HortusFox - Installation</h2>
                </div>

                <div class="quotes">
                    <blockquote>
                        « <?= QUOTE_LIST[rand(0, count(QUOTE_LIST) - 1)] ?> »
                    </blockquote>
                </div>

                <?php if ((!isset($_GET['section'])) || (strlen($_GET['section']) === 0)) { ?>
                    <div class="install-content is-center-fix">
                        <p>
                            Welcome to the installation of HortusFox. The setup wizard will guide you through the installation process.
                        </p>

                        <p>
                            If you need any further help or resources, be sure to check out the <a href="<?= APP_SERVICE_URL ?>">official HortusFox homepage</a>.
                        </p>

                        <p>
                            <a class="button is-link button-stretched" href="javascript:void(0);" onclick="window.gotoSection('deps');">Proceed with Installation</a>
                        </p>

                        <?php if (APP_SPONSOR_BUTTON) { ?>
                            <div class="install-sponsoring">
                                <div>
                                    <small>Consider a small donation if you like HortusFox</small>
                                </div>

                                <div>
                                    <a href='https://ko-fi.com/C0C7V2ESD' target='_blank'><img height='36' style='border:0px;height:36px;' src='https://storage.ko-fi.com/cdn/kofi2.png?v=3' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else if ($_GET['section'] === 'deps') { ?>
                    <div class="install-content">
                        <table>
                            <thead>
                                <tr>
                                    <td>Dependency</td>
                                    <td class="is-right">Status</td>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>PHP 8.2</td>
                                    <td class="is-right"><?= ((phpversion() >= '8.2') ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">8.2 required, yours: ' . phpversion() . '</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: mbstring</td>
                                    <td class="is-right"><?= ((extension_loaded('mbstring')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: pdo_mysql</td>
                                    <td class="is-right"><?= ((extension_loaded('pdo_mysql')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: bz2</td>
                                    <td class="is-right"><?= ((extension_loaded('bz2')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: zip</td>
                                    <td class="is-right"><?= ((extension_loaded('zip')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: curl</td>
                                    <td class="is-right"><?= ((extension_loaded('curl')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: intl</td>
                                    <td class="is-right"><?= ((extension_loaded('intl')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: bcmath</td>
                                    <td class="is-right"><?= ((extension_loaded('bcmath')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: exif</td>
                                    <td class="is-right"><?= ((extension_loaded('exif')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>

                                <tr>
                                    <td>Extension: gd</td>
                                    <td class="is-right"><?= ((extension_loaded('gd')) ? '<span class="is-successful"><i class="fas fa-check-circle"></i></span>': '<span class="is-insufficient">Insufficient</span>') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="install-action">
                        <a class="button is-link button-stretched" href="javascript:void(0);" onclick="window.gotoSection('data');">Proceed</a>
                    </div>
                <?php } else if ($_GET['section'] === 'data') { ?>
                    <div class="install-content">
                        <div class="install-error <?= ((!isset($_GET['error'])) ? 'is-hidden' : '') ?>"><?= ((isset($_GET['error'])) ? $_GET['error'] : '') ?></div>

                        <form method="POST" action="/install/index.php" onsubmit="document.querySelector('#anchor-submit').innerHTML = '<i class=\'fas fa-spinner fa-spin\'></i>';">
                            <div class="field">
                                <label class="label">Workspace name</label>
                                <div class="control">
                                    <input type="text" class="input" name="workspace" value="My Home" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Your Name</label>
                                <div class="control">
                                    <input type="text" class="input" name="name" value="Your Name" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Your E-Mail</label>
                                <div class="control">
                                    <input type="email" class="input" name="email" value="mail@domain.com" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Your Password</label>
                                <div class="control">
                                    <input type="password" class="input" name="password" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">MySQL Host</label>
                                <div class="control">
                                    <input type="text" class="input" name="dbhost" value="localhost" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">MySQL Port</label>
                                <div class="control">
                                    <input type="text" class="input" name="dbport" value="3306" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Database</label>
                                <div class="control">
                                    <input type="text" class="input" name="dbdatabase" value="hortusfox" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">User</label>
                                <div class="control">
                                    <input type="text" class="input" name="dbuser" value="root" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Password</label>
                                <div class="control">
                                    <input type="password" class="input" name="dbpassword">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">SMTP Host</label>
                                <div class="control">
                                    <input type="text" class="input" name="smtphost">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">SMTP Port</label>
                                <div class="control">
                                    <input type="text" class="input" name="smtpport">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">SMTP Address</label>
                                <div class="control">
                                    <input type="text" class="input" name="smtpaddr">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">SMTP Username</label>
                                <div class="control">
                                    <input type="text" class="input" name="smtpuser">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">SMTP Password</label>
                                <div class="control">
                                    <input type="password" class="input" name="smtppw">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input type="submit" class="is-hidden" id="btnSubmit"/>
                                    <a href="javascript:void(0);" class="button is-success is-full-width" id="anchor-submit" onclick="document.querySelector('#btnSubmit').click();">Install!</a>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>

                <div class="footer">
                    <div class="footer-info">
                        &copy; <?= date('Y') ?> by Daniel Brendel
                    </div>

                    <div class="footer-social">
                        <a href="<?= APP_GITHUB_URL ?>" target="_blank">Visit on GitHub</a>
                    </div>
                </div>
            </div>
        </body>
    </html>
<?php } else { ?>
    <?php
        function validateArg($arg, $name)
        {
            if ($arg === null) {
                header('Location: /install/index.php?section=data&error=' . urlencode($name . ' must be set'));
                exit();
            }
        }

        function getParamType($param)
        {
            switch (gettype($param)) {
                case 'boolean':
                    return \PDO::PARAM_BOOL;
                    break;
                case 'integer':
                    return \PDO::PARAM_INT;
                    break;
                case 'NULL':
                    return \PDO::PARAM_NULL;
                    break;
                case 'string':
                    return \PDO::PARAM_STR;
                    break;
                case 'double':
                    return \PDO::PARAM_STR;
                    break;
            }

            return \PDO::PARAM_STR;
        }

        function dbquery($pdo, $qry, $opt = null)
        {
            $prp = $pdo->prepare($qry);

            if ($opt !== null) {
                foreach ($opt as $key => $item) {
                    $prp->bindValue($key + 1, $item, getParamType($item));
                }
            }

            $prp->execute();

            $error = $pdo->errorInfo();
            if ($error[0] !== '00000') {
                throw new \Exception('SQL error: ' . $error[0] . ':' . $error[1] . ' -> ' . $error[2]);
            }

            $result = $prp->fetchAll();

            return $result;
        }

        function install($workspace, $name, $email, $password, $dbhost, $dbport, $dbdatabase, $dbuser, $dbpassword, $smtphost, $smtpport, $smtpaddr, $smtpuser, $smtppw)
        {
            if (!is_dir(__DIR__ . '/../../vendor')) {
                system('composer install --working-dir=' . __DIR__ . '/../../');
            }

            $env = '# App settings' . PHP_EOL;
            $env .= 'APP_NAME="HortusFox"' . PHP_EOL;
            $env .= 'APP_VERSION="1.0"' . PHP_EOL;
            $env .= 'APP_AUTHOR="Daniel Brendel"' . PHP_EOL;
            $env .= 'APP_CONTACT="dbrendel1988@gmail.com"' . PHP_EOL;
            $env .= 'APP_DEBUG=true' . PHP_EOL;
            $env .= 'APP_BASEDIR=""' . PHP_EOL;
            $env .= 'APP_LANG="en"' . PHP_EOL;
            $env .= 'APP_WORKSPACE="' . $workspace . '"' . PHP_EOL;
            $env .= 'APP_OVERLAYALPHA=null' . PHP_EOL;
            $env .= 'APP_ENABLESCROLLER=true' . PHP_EOL;
            $env .= 'APP_ENABLECHAT=true' . PHP_EOL;
            $env .= 'APP_ONLINEMINUTELIMIT=5' . PHP_EOL;
            $env .= 'APP_SHOWCHATONLINEUSERS=false' . PHP_EOL;
            $env .= 'APP_SHOWCHATTYPINGINDICATOR=false' . PHP_EOL;
            $env .= 'APP_OVERDUETASK_HOURS=10' . PHP_EOL;
            $env .= 'APP_CRONPW=null' . PHP_EOL;
            $env .= 'APP_CRONJOB_MAILLIMIT=5' . PHP_EOL;
            $env .= 'APP_GITHUB_URL="' . APP_GITHUB_URL . '"' . PHP_EOL;
            $env .= 'APP_SERVICE_URL="' . APP_SERVICE_URL . '"' . PHP_EOL;
            $env .= 'APP_ENABLEHISTORY=true' . PHP_EOL;
            $env .= 'APP_HISTORY_NAME="History"' . PHP_EOL;
            $env .= 'APP_ENABLE_PHOTO_SHARE=false' . PHP_EOL;
            $env .= '' . PHP_EOL;
            $env .= '# Session' . PHP_EOL;
            $env .= 'SESSION_ENABLE=true' . PHP_EOL;
            $env .= 'SESSION_DURATION=31536000' . PHP_EOL;
            $env .= 'SESSION_NAME=null' . PHP_EOL;
            $env .= '' . PHP_EOL;
            $env .= '# Photo resize factors' . PHP_EOL;
            $env .= 'PHOTO_RESIZE_FACTOR_DEFAULT=1.0' . PHP_EOL;
            $env .= 'PHOTO_RESIZE_FACTOR_1=0.5' . PHP_EOL;
            $env .= 'PHOTO_RESIZE_FACTOR_2=0.4' . PHP_EOL;
            $env .= 'PHOTO_RESIZE_FACTOR_3=0.4' . PHP_EOL;
            $env .= 'PHOTO_RESIZE_FACTOR_4=0.3' . PHP_EOL;
            $env .= 'PHOTO_RESIZE_FACTOR_5=0.2' . PHP_EOL;
            $env .= '' . PHP_EOL;
            $env .= '# Database settings' . PHP_EOL;
            $env .= 'DB_ENABLE=true' . PHP_EOL;
            $env .= 'DB_HOST="' . $dbhost . '"' . PHP_EOL;
            $env .= 'DB_USER="' . $dbuser . '"' . PHP_EOL;
            $env .= 'DB_PASSWORD="' . $dbpassword . '"' . PHP_EOL;
            $env .= 'DB_PORT=' . $dbport . PHP_EOL;
            $env .= 'DB_DATABASE="' . $dbdatabase . '"' . PHP_EOL;
            $env .= 'DB_DRIVER=mysql' . PHP_EOL;
            $env .= 'DB_CHARSET="utf8mb4"' . PHP_EOL;
            $env .= '' . PHP_EOL;
            $env .= '# SMTP settings' . PHP_EOL;
            $env .= 'SMTP_FROMNAME="' . $smtpaddr . '"' . PHP_EOL;
            $env .= 'SMTP_FROMADDRESS="' . $smtpaddr . '"' . PHP_EOL;
            $env .= 'SMTP_HOST="' . $smtphost . '"' . PHP_EOL;
            $env .= 'SMTP_PORT=' . $smtpport . PHP_EOL;
            $env .= 'SMTP_USERNAME="' . $smtpuser . '"' . PHP_EOL;
            $env .= 'SMTP_PASSWORD="' . $smtppw . '"' . PHP_EOL;
            $env .= 'SMTP_ENCRYPTION=tls' . PHP_EOL;
            $env .= '' . PHP_EOL;
            $env .= '# Logging' . PHP_EOL;
            $env .= 'LOG_ENABLE=true' . PHP_EOL;

            file_put_contents(__DIR__ . '/../../.env', $env);

            $pdo = new \PDO('mysql:host=' . $dbhost . ';port=' . $dbport . ';dbname=' . $dbdatabase . ';charset=utf8', $dbuser, $dbpassword);

            dbquery($pdo, 'CREATE DATABASE IF NOT EXISTS `' . $dbdatabase . '`');

            $curdir = getcwd();
            chdir(__DIR__ . '/../../');

            system('php asatru migrate:fresh');
            system('php asatru calendar:classes');

            dbquery($pdo, 'INSERT INTO `AppModel` (id, workspace, language, scroller, chat_enable, chat_timelimit, chat_showusers, chat_indicator, chat_system, history_enable, history_name, enable_media_share, cronjob_pw, overlay_alpha, smtp_fromname, smtp_fromaddress, smtp_host, smtp_port, smtp_username, smtp_password, smtp_encryption, pwa_enable, owm_enable, owm_api_key, owm_latitude, owm_longitude, owm_unittype, owm_cache, created_at) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)', [
                $workspace, 'en', true, true, 5, true, false, true, true, 'History', false, md5(random_bytes(55) . date('Y-m-d H:i:s')), null, $smtpaddr, $smtpaddr, $smtphost, $smtpport, $smtpuser, $smtppw, 'tls', 0, 0, null, null, null, 'default', 300
            ]);

            dbquery($pdo, 'INSERT INTO `users` (name, email, password, admin) VALUES(?, ?, ?, ?)', [
                $name, $email, password_hash($password, PASSWORD_BCRYPT), true
            ]);

            chdir($curdir);

            unlink(__DIR__ . '/../../do_install');
        }

        $workspace = (isset($_POST['workspace'])) ? $_POST['workspace'] : null;
        $name = (isset($_POST['name'])) ? $_POST['name'] : null;
        $email = (isset($_POST['email'])) ? $_POST['email'] : null;
        $password = (isset($_POST['password'])) ? $_POST['password'] : null;
        $dbhost = (isset($_POST['dbhost'])) ? $_POST['dbhost'] : null;
        $dbport = (isset($_POST['dbport'])) ? $_POST['dbport'] : null;
        $dbdatabase = (isset($_POST['dbdatabase'])) ? $_POST['dbdatabase'] : null;
        $dbuser = (isset($_POST['dbuser'])) ? $_POST['dbuser'] : null;
        $dbpassword = (isset($_POST['dbpassword'])) ? $_POST['dbpassword'] : '';
        $smtphost = (isset($_POST['smtphost'])) ? $_POST['smtphost'] : '';
        $smtpport = (isset($_POST['smtpport'])) ? $_POST['smtpport'] : 587;
        $smtpaddr = (isset($_POST['smtpaddr'])) ? $_POST['smtpaddr'] : '';
        $smtpuser = (isset($_POST['smtpuser'])) ? $_POST['smtpuser'] : '';
        $smtppw = (isset($_POST['smtppw'])) ? $_POST['smtppw'] : '';

        validateArg($workspace, 'workspace');
        validateArg($name, 'name');
        validateArg($email, 'email');
        validateArg($password, 'password');
        validateArg($dbhost, 'dbhost');
        validateArg($dbport, 'dbport');
        validateArg($dbdatabase, 'dbdatabase');
        validateArg($dbuser, 'dbuser');
        validateArg($dbpassword, 'dbpassword');

        install($workspace, $name, $email, $password, $dbhost, $dbport, $dbdatabase, $dbuser, $dbpassword, $smtphost, $smtpport, $smtpaddr, $smtpuser, $smtppw);

        header('Location: /');
        exit();
    ?>
<?php } ?>