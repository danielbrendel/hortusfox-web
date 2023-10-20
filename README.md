# HortusFox - A self-hosted collaborative plant management system

(C) 2023 by Daniel Brendel

**Contact**: dbrendel1988(at)gmail(dot)com\
**GitHub**: https://github.com/danielbrendel

Released under the MIT license

## Description
HortusFox is a self-hosted collaborative plant management system which you can use in your own environment to manage all your plants.
You can add your plants with various details and photos and assign them to a location of your environment. There is a dashboard
available that shows all important overview information. The system does also feature a warning system in order to indicate
which plants need special care, user authentication, tasks, inventory management, collaborative chat and a history log of what actions 
users have taken. The system features collaborative management, so you can manage your plants with multiple users.

## Installation
In order to install HortusFox you need to first setup a PHP environment along with a MySQL database
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
```
After saving the file you can now let the product create all neccessary tables via the following command:
```shell
php asatru migrate:fresh
```
You might now want to start your web server to host the application. If you want to quickly use the inbuilt webserver
you can start it via:
```shell
php asatru serve
```
Now browse to http://localhost:8000/ and you should see a message indicating that the access is forbidden with error 403.
At this point you need to create your database users. Go to your database control panel and switch to the users table.
Add all new users that should get access to the application. The following is an example:
```sql
INSERT INTO `users` (`id`, `name`, `email`, `token`, `lang`, `chatcolor`, `show_log`, `last_seen_msg`, `last_action`, `created_at`) VALUES
(
    NULL, 
    'Username', 
    'name@example.com', 
    'your_auth_token_here', 
    NULL, 
    NULL, 
    1, 
    NULL, 
    CURRENT_TIMESTAMP, 
    CURRENT_TIMESTAMP
);
```
As you might have noticed the values that you need to customize are name, email and token. All others are left with their default values.
The auth token must be created manually. For testing purposes you might just want to quickly use something like:
```shell
php -r "echo md5(random_bytes(55) . date('Y-m-d H:i:s'));"
```
If you want to test it now you can again browse to the URL and then open the console and set your auth cookie:
```javascript
document.cookie = 'auth_token=your_auth_token_here; path=/';
```
If everything went right, you should then see your dashboard. You next step is to build the <a href="https://github.com/danielbrendel/hortusfox-app-android">mobile app</a> for your users.

## System requirements
- PHP ^8.2
- MySQL (10.4.27-MariaDB or similar)
- Standard PHP extensions