<h1 align="center">
    <img src="public/logo.png" width="256"/><br/>
    HortusFox
</h1>

<p align="center">
    A self-hosted collaborative plant management system<br/>
    (C) 2023 - 2024 by Daniel Brendel
</p>

<p align="center">
    <a href="https://www.hortusfox.com/">www.hortusfox.com</a>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/web-php-orange" alt="web-php"/>
    <img src="https://img.shields.io/badge/db-mysql-pink" alt="db-mysql"/>
    <img src="https://img.shields.io/badge/license-MIT-blue" alt="license-mit"/>
    <img src="https://img.shields.io/badge/maintained-yes-green" alt="maintained-yes"/>
</p>


<p align="center">
    <a href='https://ko-fi.com/C0C7V2ESD' target='_blank'><img height='36' style='border:0px;height:36px;' src='https://storage.ko-fi.com/cdn/kofi2.png?v=3' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>
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
- [System requirements](#system-requirements)
- [Contributing](#contributing)

## Description

HortusFox is a self-hosted collaborative plant management system which you can use in your own environment to manage all your plants.
You can add your plants with various details and photos and assign them to a location of your environment. There is a dashboard
available that shows all important overview information. The system does also feature a warning system in order to indicate
which plants need special care, user authentication, tasks, inventory management, collaborative chat and a history log of what actions
users have taken. The system features collaborative management, so you can manage your plants with multiple users.

## Features

- 🪴 Plant management
- 🏠 Custom locations
- 📜 Tasks system
- 📖 Inventory system
- 🔍 Search feature
- 🕰️ History feature
- 💬 Group chat
- ⚙️ Profile management
- 🦋 Themes
- 🔑 Admin dashboard
- 📢 Reminders
- 💾 Backups

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

2. Set your admin account in the `docker-compose.yml`.
```yaml
APP_ADMIN_EMAIL: "admin@example.com"
APP_ADMIN_PASSWORD: "password"
```

3. Pull the image and run the application:

```shell
docker-compose pull
docker-compose up -d
```

4. The application should now be running on http://localhost:8080.

5. You can now go to the <a href="http://localhost:8080/admin">admin dashboard</a> in order to adjust your workspace settings and also go to your <a href="http://localhost:8080/profile">profile page</a> in order to adjust your user preferences. 

Hint: You should use a file system <i>not</i> like NTFS due to it can't deal with Unix ownerships, groups & permissions properly.

### Installer

You can also use the integrated installer in order to install the product. In order for that to do, be sure that you are not running the
system via the internal asatru development server. Instead you might want to, for instance, run the system from the context of a webserver
environment like XAMPP. If you do that, just create a file named do_install (no file extension) in the root directory of the project and
browse to the installer and the system will guide you through the installation process.

```
http://localhost/install
```

Be sure that PHP is installed and both your webserver and mysql server are running. If there is no vendor folder already created then
the system will try to run Composer in order to install all required dependencies. For that to work you need to have Composer installed
on your system. Altough the system tries to create the database for you, sometimes this might fail, so you will have to create the
database before running the installation. However all table migrations will then be created by the system. The system can then be managed
via the admin section (e.g. environment settings, users, locations).

### Manual

In order to manually install HortusFox you need to first setup a PHP environment along with a MySQL database
and also Composer. Afterwards you can clone or download the repository. Then go to the root directory
of the project and let Composer install the required dependencies. Note: It is recommended to setup the
actual production environment on, e.g., a home server. There are various solutions available for this.

```shell
composer install
```

Now we need to configure the project. Create a .env file from the .env.example, open it and manage the following variables:

```sh
# Here you can set your default language. However users have the opportunity to set their personal language
APP_LANG="en"

# This is the name of the workspace, e.g. your home
APP_WORKSPACE="My home"

# This determines if the scroller shall be shown. It is useful to quickly (but smoothly) scroll to top
APP_ENABLESCROLLER=true

# Determines the amount of minutes that is used within the calculation of a users online status. You can leave it as is
APP_ONLINEMINUTELIMIT=5

# Use this setting if you want to show or hide the current online user list in the chat
APP_SHOWCHATONLINEUSERS=false

# Use this setting if you want to show or hide an indiactor if someone types a chat message
APP_SHOWCHATTYPINGINDICATOR=false

# The authentication token to be used to request cronjobs. Set this to a token of your choice
APP_CRONPW="your-auth-token"

# This must be set to true for the product to work in order to enable database connection
DB_ENABLE=true

# Enter your hostname or IP address of your MySQL database server
DB_HOST=localhost

# Enter the database username
DB_USER=root

# Enter the database user password
DB_PASSWORD=""

# Database connection port. Normally this doesn't need to be changed
DB_PORT=3306

# The actual database of your MySQL server to be used
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

After saving the file you can now let the product create all neccessary tables via the following command:

```shell
php asatru migrate:fresh
```

Now you need to insert your initial app settings profile into the database. These settings can be later adjusted in the admin dashboard.
```sql
INSERT INTO `AppModel` (id, workspace, language, scroller, chat_enable, chat_timelimit, chat_showusers, chat_indicator, chat_system, history_enable, history_name, enable_media_share, cronjob_pw, overlay_alpha, smtp_fromname, smtp_fromaddress, smtp_host, smtp_port, smtp_username, smtp_password, smtp_encryption, pwa_enable, created_at) VALUES (
    NULL, 
    'My workspace name', 
    'en', 
    1, 
    1, 
    5, 
    1, 
    0,
    1, 
    1, 
    'History', 
    0, 
    'a-secret-pw', 
    null, 
    '', 
    '', 
    '', 
    587, 
    '', 
    '', 
    'tls',
    0,
    CURRENT_TIMESTAMP
)
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
INSERT INTO `users` (`id`, `name`, `email`, `password`, `password_reset`, `session`, `status`, `admin`, `lang`, `chatcolor`, `show_log`, `show_plants_aoru`, `notify_tasks_overdue`, `notify_tasks_tomorrow`, `last_seen_msg`, `last_typing`, `last_action`, `created_at`) VALUES
(
    NULL,
    'Username',
    'name@example.com',
    'your_password_token_here',
    NULL,
    NULL,
    0,
    1,
    NULL,
    NULL,
    1,
    1,
    1,
    1,
    NULL,
    NULL,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
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
Each new created user will get a confirmation e-mail with an automatically generated password in order to log in. It is recommended that users change
their passwords after their first login.
Last but not least you need to add all your locations of your local environment to the database. You can do that either via the admin section or
manually by inserting entries into the locations table.

```sql
INSERT INTO `locations` (`id`, `name`, `icon`, `active`, `created_at`) VALUES
(
    NULL,
    'Name of location',
    'fas fa-leaf',
    1,
    CURRENT_TIMESTAMP
);
```

The mandatory fields are name of location (e.g. garden, living room, kitchen, etc) as well as the FontAwesome icon to be used.
You can use all free FontAwesome icons (v5.15.4 free icons). For a complete list of available icons, visit the <a href="https://fontawesome.com/v5/search?m=free">FontAwesome search page</a>. Note that you can then manage various aspects of the system via the admin section when logged in as a user with admin privileges.
Additionally you might want to build the <a href="https://github.com/danielbrendel/hortusfox-app-android">android mobile app</a> for your users.

## Cronjobs

Cronjobs are used to regularly execute a specific task. For each cronjob you need to set the cronjob parameter with your token set via `AppModel.cronjob_pw`. The following cronjobs are available:

```sh
# Used to inform users about overdue tasks. Should be called multiple times per day.
GET /cronjob/tasks/overdue?cronpw={your-auth-token}

# Used to inform users about tasks that are due tomorrow. Should be called multiple times per day.
GET /cronjob/tasks/tomorrow?cronpw={your-auth-token}
```

## System requirements

- PHP ^8.2
- MySQL (10.4.27-MariaDB or similar)
- SMTP server for e-mailing
- Docker with Docker-Compose for containerization

## Contributing

Please view the [contribution guidelines](CONTRIBUTING.md) if you intend to contribute to our repository.
