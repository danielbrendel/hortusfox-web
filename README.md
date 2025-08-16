<h1 align="center">
    <img src="public/logo.png" width="256"/><br/>
    HortusFox
</h1>

<p align="center">
    A self-hosted collaborative plant management system<br/>
    (C) 2023 - 2025 by Daniel Brendel
</p>

<p align="center">
    <a href="https://www.hortusfox.com/">www.hortusfox.com</a>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/web-php-orange" alt="web-php"/>
    <img src="https://img.shields.io/badge/db-mariadb-pink" alt="db-mariadb"/>
    <img src="https://img.shields.io/badge/license-MIT-blue" alt="license-mit"/>
    <img src="https://img.shields.io/badge/maintained-yes-green" alt="maintained-yes"/>
</p>

<p align="center">
    <a href="https://discord.gg/kc6xGmjzVS"><img src="https://img.shields.io/badge/discord-5715BA?style=for-the-badge&logoColor=white" alt="discord"></a>
    <a href="https://mastodon.social/@hortusfox"><img src="https://img.shields.io/badge/mastodon-D1550A?style=for-the-badge&logoColor=white" alt="mastodon"></a>
    <a href="https://www.hortusfox.com/videos"><img src="https://img.shields.io/badge/videos-red?style=for-the-badge&logoColor=white" alt="videos"></a>
    <a href="https://www.hortusfox.com/faq"><img src="https://img.shields.io/badge/faq-yellow?style=for-the-badge&logoColor=white" alt="faq"></a>
</p>

<p align="center">
    <a href="https://github.com/sponsors/danielbrendel" target="_blank"><img height="35" style="border:0px;height:35px;" src="https://img.shields.io/static/v1?label=Sponsor&message=%E2%9D%A4&logo=GitHub&color=%23fe8e86" alt="Sponsor me on GitHub"/></a>&nbsp;
    <a href='https://ko-fi.com/danielbrendel' target='_blank'><img height='35' style='border:0px;height:35px;' src='https://storage.ko-fi.com/cdn/kofi2.png?v=3' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>
</p>

<p align="center">
    <img src="app/resources/gfx/screenshot-desktop.png"/><br/>
</p>

## Table of Contents

- [Description](#description)
- [Features](#features)
- [Resources](#resources)
- [Installation](#installation)
  - [Docker](#docker)
  - [Installer](#installer)
  - [Manual](#manual)
- [Cronjobs](#cronjobs)
- [Application testing](#application-testing)
- [System requirements](#system-requirements)
- [External Services](#external-services)
  - [App Icons](#app-icons)
  - [Weather Forecast](#weather-forecast)
  - [Plant Identification](#plant-identification)
  - [Plant information](#plant-information)
- [Contributing](#contributing)
- [Security](#security)

## Description

HortusFox is a self-hosted collaborative plant management system which you can use in your own environment to manage all your plants.
You can add your plants with various details and photos and assign them to a location of your environment. There is a dashboard
available that shows all important overview information. The system does also feature a warning system in order to indicate
which plants need special care, user authentication, tasks, inventory management, calendar, collaborative chat and a history log of 
what actions users have taken. The system features collaborative management, so you can manage your plants with multiple users.
There are many more features. You can see a list of features below.

## Features

- 🪴 Plant management
- 🏠 Custom locations
- 📜 Tasks system
- 📖 Inventory system
- 📆 Calendar system
- 🔍 Search feature
- 🕰️ History feature
- 🌦️ Weather feature
- 💬 Group chat
- ⚙️ Profile management
- 🦋 Themes
- 🔑 Admin dashboard
- 📢 Reminders
- 💾 Backups
- 💻 REST API
- 🔬 Plant identification

## Resources

- [Official Homepage](https://www.hortusfox.com/)
- [Documentation](https://hortusfox.github.io/)

## Installation

### Docker

Using Docker and Docker Compose simplifies the setup process and ensures consistency across different environments.

Prerequisites

- Docker installed on your system
- Docker Compose installed on your system

Follow these steps:

1. Clone the repository:

```shell
git clone https://github.com/danielbrendel/hortusfox-web.git
cd hortusfox-web
```

2. Set environment variables in the `docker-compose.yml`.

Set your admin user e-mail and password in order to login
```yaml
# Admin login credentials
# services.app.environment
APP_ADMIN_EMAIL: "admin@example.com"
APP_ADMIN_PASSWORD: "password"
```

Set your preferred timezone. Default is UTC.
```yaml
# Timezone setting
# services.app.environment
APP_TIMEZONE: "UTC"
```

If you want composer dependencies to be updated when the app container starts, set the following setting
```yaml
# Update composer dependencies during start if set to true
# services.app.environment
APP_UPDATEDEPS: "true"
```

Set database settings if required. It is recommended to set custom passwords due to security reasons. All other settings can be left unaltered.
```yaml
# Settings used to establish connections to the database
# services.app.environment
DB_HOST: db
DB_PORT: 3306
DB_DATABASE: hortusfox
DB_USERNAME: user
DB_PASSWORD: password
DB_CHARSET: "utf8mb4"

# Settings of the database container
# services.db.environment
MARIADB_ROOT_PASSWORD: my-secret-pw
MARIADB_DATABASE: hortusfox
MARIADB_USER: user
MARIADB_PASSWORD: password
```

You can optionally specify proxy authentication settings for your app
```yaml
# services.app.environment
PROXY_ENABLE: "true"
PROXY_HEADER_EMAIL: "Your e-mail header identifier here"
PROXY_HEADER_USERNAME: "Your username header identifier here"
PROXY_AUTO_SIGNUP: "true"
PROXY_WHITELIST: ""
PROXY_HIDE_LOGOUT: "true"
```

3. Pull the image and run the application:

```shell
docker compose pull
docker compose up -d
```

note: docker v1 syntax uses "docker-compose" instead of v2's "docker compose"

4. The application should now be running on http://localhost:8080.

5. You can now go to the <a href="http://localhost:8080/admin">admin dashboard</a> in order to adjust your workspace settings and also go to your <a href="http://localhost:8080/profile">profile page</a> in order to adjust your user preferences.

### Installer

You can also use the integrated installer in order to install the product. In order for that to do, be sure that you are not running the
system via the internal asatru development server. Instead you might want to, for instance, run the system from the context of a webserver
environment like XAMPP. If you do that, just create a file named do_install (no file extension) in the root directory of the project and
browse to the installer and the system will guide you through the installation process.

```
http://localhost/install
```

Be sure that PHP is installed and both your webserver and mariadb server are running. If there is no vendor folder already created then
the system will try to run Composer in order to install all required dependencies. For that to work you need to have Composer installed
on your system. Although the system tries to create the database for you, sometimes this might fail, so you will have to create the
database before running the installation. However all table migrations will then be created by the system. The system can then be managed
via the admin section (e.g. environment settings, users, locations).

### Manual

In order to manually install HortusFox you need to first setup a PHP environment along with a MariaDB database
and also Composer. Afterwards you can clone or download the repository. Then go to the root directory
of the project and let Composer install the required dependencies.

```shell
composer install
```

Now we need to configure the project. Create a .env file from the .env.example, open it and manage the following variables:

```sh
# URL to the official service backend. Used for e.g. version comparison
APP_SERVICE_URL="https://www.hortusfox.com"

# URL to the repository of the project
APP_GITHUB_URL="https://github.com/danielbrendel/hortusfox-web"

# This must be set to true for the product to work in order to enable database connection
DB_ENABLE=true

# Enter your hostname or IP address of your MariaDB database server
DB_HOST=localhost

# Enter the database username
DB_USER=root

# Enter the database user password
DB_PASSWORD=""

# Database connection port. Normally this doesn't need to be changed
DB_PORT=3306

# The actual database of your MariaDB server to be used
DB_DATABASE=hortusfox

# Database driver. This needs to be unaltered for now
DB_DRIVER=mysql

# The name of the e-mail sender
SMTP_FROMNAME="Test"

# The e-mail address of the sender
SMTP_FROMADDRESS="test@domain.tld"

# Hostname or address to your SMTP mail provider
SMTP_HOST=""

# Port to be used for connecting to the host
SMTP_PORT=587

# Your SMTP username
SMTP_USERNAME=""

# Your SMTP password for authentication
SMTP_PASSWORD=""

# Communication encryption
SMTP_ENCRYPTION=tls
```

After saving the file you can now let the product create all necessary tables via the following command:

```shell
php asatru migrate:fresh
```

Now you need to insert your initial app settings profile into the database. There are many more settings available, but you can adjust them later in the admin dashboard.
```sql
INSERT INTO `AppModel` (id, workspace, language, created_at) VALUES (
    NULL, 
    'My workspace name', 
    'en', 
    CURRENT_TIMESTAMP
);
```

Next you should let the system perform some initial operations
```shell
# Add default plant attributes
php asatru plants:attributes

# Add default calendar classes
php asatru calendar:classes
```

You might now want to start your web server to host the application. If you want to quickly use the inbuilt webserver
you can start it via:

```shell
php asatru serve
```

Now browse to http://localhost:8000/ and you should be redirected to the authentication page.
At this point you need to create your first user. Go to your database control panel and switch to the users table.
Add the user account that should get access to the application with admin privileges. The following is an example:

```sql
INSERT INTO `UserModel` (`name`, `email`, `password`, `admin`) VALUES
(
    'Username',
    'name@example.com',
    'your_password_token_here',
    1
);
```

As you might have noticed the values that you need to customize are name, email, password and admin. All others are left with their default values.
The password hash must be created manually. For testing purposes you might just want to quickly use something like:

```shell
php -r "echo password_hash('test', PASSWORD_BCRYPT);"
```

You may now login with your initial admin user account using your e-mail address and the password of which you have stored the hash in the table.
After logging in, you should then be redirected to the dashboard. Further users can now be created via the admin area. Users can change their
passwords in their profile preferences. They can also reset their password. Therefore an e-mail will be sent to them with restoration instructions.
Each new created user can get a confirmation e-mail with an automatically generated password in order to log in. It is recommended that users change
their passwords after their first login.
Last but not least you need to add all your locations of your local environment to the database. You can do that either via the admin section or
manually by inserting entries into the locations table.

```sql
INSERT INTO `LocationsModel` (`id`, `name`, `icon`, `active`, `created_at`) VALUES
(
    NULL,
    'Name of location',
    NULL,
    1,
    CURRENT_TIMESTAMP
);
```

The mandatory field is the name of the location (e.g. garden, living room, kitchen, etc).

## Cronjobs

Cronjobs are used to regularly execute a specific task. For each cronjob you need to set the cronjob parameter with your token set via `AppModel.cronjob_pw`. The following cronjobs are available:

```sh
# Used to inform users about overdue tasks. Should be called multiple times per day.
GET /cronjob/tasks/overdue?cronpw={your-auth-token}

# Used to inform users about tasks that are due tomorrow. Should be called multiple times per day.
GET /cronjob/tasks/tomorrow?cronpw={your-auth-token}

# Used to check for recurring tasks and reset them accordingly. Should be called at least once, or better multiple times per day.
GET /cronjob/tasks/recurring?cronpw={your-auth-token}

# Used to inform users about due calendar dates
GET /cronjob/calendar/reminder?cronpw={your-auth-token}

# Used to perform the automatic backup of your workspace data
GET /cronjob/backup/auto?cronpw={your-auth-token}
```

## Application testing

**WARNING: Application testing is currently under construction and will progress with further development.**

### Introduction

Application tests are useful to verify that specific parts of the application are still working after an update.

There are two types of tests whose purpose depend on the use case.

### Feature

Feature tests are used to test an entire workflow. In order to achieve this, you simulate a call to a specific route of the application.
Afterwards the returned result is checked if it matches the assumption. The assumption can depend on the calling context and request data (if any).
Often calling a route will encompass various parts of the application during the process, such as Models, Modules, Helper functions, etc.

### Unit

Unit tests are useful to test against specific parts of the application. For instance, you can test various methods of a Model or a Module. 
This is especially useful if you employ a test-driven-development approach to verify that a method works as expected in various scenarios.

### Running the tests

In order to run all application tests, please issue the following command in the context of the root project directory:
```sh
"vendor/bin/phpunit" --stderr
```

In order to run the Feature suite, please issue the following command:
```sh
"vendor/bin/phpunit" --stderr --testsuite Feature
```

In order to run the Unit suite, please issue the following command:
```sh
"vendor/bin/phpunit" --stderr --testsuite Unit
```

### Test data

Testing data should be specified as environment variables in the `php` section via the `phpunit.xml`.

## System requirements

- PHP ^8.3
- MariaDB ^11.4
- SMTP server for e-mailing
- Docker with Docker Compose for containerization

## External Services

There are features that rely on external services. Some of these features are mandatory, while others can be enabled if desired.

### App icons

The project uses <a href="https://fontawesome.com/">FontAwesome</a> free to display icons on various occasions. Please see the free license <a href="https://fontawesome.com/license/free">here</a>.

### Weather forecast

In order to provide the weather forecast feature, the project uses [OpenWeatherMap](https://openweathermap.org/) to get the weather data. 
In order to use this feature you need to create an account and get your own API key.

### Plant identification

The project uses the [Pl@ntNet API](https://my.plantnet.org/) to identify plants. In order to use this feature you need to create an account and get your own API key.

### Plant information

The project uses the [Global Biodiversity Information Facility](https://techdocs.gbif.org/en/) in order to query plant data on various occasions. As of now you do not need to obtain an extra API key in order for this to work.

## Contributing

Please view the [contribution guidelines](CONTRIBUTING.md) if you intend to contribute to this repository.

## Security

If you discover any security vulnerability, please refer to the [security guidelines](SECURITY.md) on how to proceed.