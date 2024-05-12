#!/bin/bash

# Default values for admin user and password
DEFAULT_ADMIN_EMAIL="admin@example.com"
DEFAULT_ADMIN_PASSWORD=$(openssl rand -base64 12)  # Generate a random password

# Default values for environment variables
DEFAULT_APP_BASE_DIR=""
DEFAULT_APP_DEBUG=true
DEFAULT_APP_LANG="en"
DEFAULT_APP_WORKSPACE="My plant home"
DEFAULT_APP_ONLINE_MINUTE_LIMIT=5
DEFAULT_APP_OVERDUE_TASK_HOURS=10
DEFAULT_APP_CRON_PW=$(openssl rand -base64 12)
DEFAULT_APP_CRON_MAIL_LIMIT=5
DEFAULT_LOG_ENABLE=true
DEFAULT_SMTP_FROMNAME="Test"
DEFAULT_SMTP_FROMADDRESS="test@domain.tld"
DEFAULT_SMTP_HOST=""
DEFAULT_SMTP_PORT=587
DEFAULT_SMTP_USERNAME=""
DEFAULT_SMTP_PASSWORD=""
DEFAULT_SMTP_ENCRYPTION="tls"

# Use environment variables if provided, otherwise use defaults
ADMIN_EMAIL="${APP_ADMIN_EMAIL:-$DEFAULT_ADMIN_EMAIL}"
ADMIN_PASSWORD="${APP_ADMIN_PASSWORD:-$DEFAULT_ADMIN_PASSWORD}"
APP_BASE_DIR="${APP_BASE_DIR:-$DEFAULT_APP_BASE_DIR}"
APP_DEBUG=${APP_DEBUG:-$DEFAULT_APP_DEBUG}
APP_LANG="${APP_LANG:-$DEFAULT_APP_LANG}"
APP_WORKSPACE="${APP_WORKSPACE:-$DEFAULT_APP_WORKSPACE}"
APP_ONLINE_MINUTE_LIMIT=${APP_ONLINE_MINUTE_LIMIT:-$DEFAULT_APP_ONLINE_MINUTE_LIMIT}
APP_OVERDUE_TASK_HOURS=${APP_OVERDUE_TASK_HOURS:-$DEFAULT_APP_OVERDUE_TASK_HOURS}
APP_CRON_PW="${APP_CRON_PW:-$DEFAULT_APP_CRON_PW}"
APP_CRON_MAIL_LIMIT=${APP_CRON_MAIL_LIMIT:-$DEFAULT_APP_CRON_MAIL_LIMIT}
LOG_ENABLE={$LOG_ENABLE:-$DEFAULT_LOG_ENABLE}
SMTP_FROMNAME="${SMTP_FROMNAME:-$DEFAULT_SMTP_FROMNAME}"
SMTP_FROMADDRESS="${SMTP_FROMADDRESS:-$DEFAULT_SMTP_FROMADDRESS}"
SMTP_HOST="${SMTP_HOST:-$DEFAULT_SMTP_HOST}"
SMTP_PORT=${SMTP_PORT:-$DEFAULT_SMTP_PORT}
SMTP_USERNAME="${SMTP_USERNAME:-$DEFAULT_SMTP_USERNAME}"
SMTP_PASSWORD="${SMTP_PASSWORD:-$DEFAULT_SMTP_PASSWORD}"
SMTP_ENCRYPTION="${SMTP_ENCRYPTION:-$DEFAULT_SMTP_ENCRYPTION}"

# Function to set PHP error reporting based on APP_DEBUG
configure_php_error_reporting() {
    if [ "$APP_DEBUG" = "false" ]; then
        # Suppress warnings and notices if APP_DEBUG is false
        echo 'error_reporting = E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED' > /usr/local/etc/php/conf.d/errors.ini
        echo 'display_errors = Off' >> /usr/local/etc/php/conf.d/errors.ini
    else
        # Show all errors if APP_DEBUG is true
        echo 'error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT' > /usr/local/etc/php/conf.d/errors.ini
        echo 'display_errors = On' >> /usr/local/etc/php/conf.d/errors.ini
    fi
}

# Function to create the .env file
create_environment_file() {
    rm -f "/var/www/html/.env"

    cat <<-EOF >> /var/www/html/.env

    # App settings
    APP_NAME="HortusFox"
    APP_DEBUG=$APP_DEBUG
    APP_BASEDIR="$APP_BASE_DIR"
    APP_LANG="$APP_LANG"
    APP_WORKSPACE="$APP_WORKSPACE"
    APP_ENABLESCROLLER="$APP_ENABLE_SCROLLER"
    APP_OVERLAYALPHA="$APP_OVERLAY_ALPHA"
    APP_ENABLECHAT="$APP_ENABLE_CHAT"
    APP_ONLINEMINUTELIMIT="$APP_ONLINE_MINUTE_LIMIT"
    APP_SHOWCHATONLINEUSERS="$APP_SHOW_CHAT_ONLINE_USERS"
    APP_SHOWCHATTYPINGINDICATOR="$APP_SHOW_CHAT_TYPING_INDICATOR"
    APP_OVERDUETASK_HOURS=$APP_OVERDUE_TASK_HOURS
    APP_CRONPW="$APP_CRON_PW"
    APP_CRONJOB_MAILLIMIT=$APP_CRON_MAIL_LIMIT
    APP_GITHUB_URL="https://github.com/danielbrendel/hortusfox-web"
    APP_SERVICE_URL="https://www.hortusfox.com"
    APP_ENABLEHISTORY=$APP_ENABLE_HISTORY
    APP_HISTORY_NAME="$APP_HISTORY_NAME"

    # Session
    SESSION_ENABLE=true
    SESSION_DURATION=31536000
    SESSION_NAME=null

    # Photo resize factors
    PHOTO_RESIZE_FACTOR_DEFAULT=$PHOTO_RESIZE_FACTOR_DEFAULT
    PHOTO_RESIZE_FACTOR_1=$PHOTO_RESIZE_FACTOR_1
    PHOTO_RESIZE_FACTOR_2=$PHOTO_RESIZE_FACTOR_2
    PHOTO_RESIZE_FACTOR_3=$PHOTO_RESIZE_FACTOR_3
    PHOTO_RESIZE_FACTOR_4=$PHOTO_RESIZE_FACTOR_4
    PHOTO_RESIZE_FACTOR_5=$PHOTO_RESIZE_FACTOR_5

    # Database settings
    DB_ENABLE=true
    DB_HOST="$DB_HOST"
    DB_USER="$DB_USERNAME"
    DB_PASSWORD="$DB_PASSWORD"
    DB_PORT=$DB_PORT
    DB_DATABASE="$DB_DATABASE"
    DB_DRIVER=mysql
    DB_CHARSET="$DB_CHARSET"
    
    # SMTP Settings
    SMTP_FROMNAME="$SMTP_FROMNAME"
    SMTP_FROMADDRESS="$SMTP_FROMADDRESS"
    SMTP_HOST="$SMTP_HOST"
    SMTP_PORT=$SMTP_PORT
    SMTP_USERNAME="$SMTP_USERNAME"
    SMTP_PASSWORD="$SMTP_PASSWORD"
    SMTP_ENCRYPTION="$SMTP_ENCRYPTION"

    # Logging
    LOG_ENABLE=$LOG_ENABLE
EOF
}

# Function to check if initial settings were created and add if not
add_initial_settings_if_missing() {
    local settings_count=$(mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" -D "$DB_DATABASE" -N -s -e "SELECT COUNT(*) FROM AppModel WHERE id=1;")
    if [[ $settings_count -gt 0 ]]; then
        echo "App settings profile already exists. Skipping creation."
    else
        echo "App settings profile does not exist. Creating..."
        create_app_settings
    fi
}

# Function to create initial settings
create_app_settings() {
    mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" -D "$DB_DATABASE" -e "INSERT INTO AppModel (id, workspace, language, cronjob_pw, smtp_fromname, smtp_fromaddress, smtp_host, smtp_port, smtp_username, smtp_password, smtp_encryption, created_at) VALUES (NULL, '$APP_WORKSPACE', '$APP_LANG', '$APP_CRON_PW', '$SMTP_FROMNAME', '$SMTP_FROMADDRESS', '$SMTP_HOST', $SMTP_PORT, '$SMTP_USERNAME', '$SMTP_PASSWORD', '$SMTP_ENCRYPTION', CURRENT_TIMESTAMP);"

    echo "App settings profile created."
}

# Function to check if the admin user exists and add if not
add_admin_user_if_missing() {
    local user_count=$(mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" -D "$DB_DATABASE" -N -s -e "SELECT COUNT(*) FROM users WHERE email='$ADMIN_EMAIL';")
    if [[ $user_count -gt 0 ]]; then
        echo "Admin user ($ADMIN_EMAIL) already exists. Skipping user creation."
    else
        echo "Admin user ($ADMIN_EMAIL) does not exist. Creating..."
        create_admin_user
    fi
}

# Function to create an admin user
create_admin_user() {
    # Use PHP to hash the password
    local hashed_password=$(php -r "echo password_hash('$ADMIN_PASSWORD', PASSWORD_BCRYPT);")

    # Insert the new admin user into the database
    mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" -D "$DB_DATABASE" -e "INSERT INTO users (id, name, email, password, password_reset, admin, lang, chatcolor, show_log, show_plants_aoru, notify_tasks_overdue, notify_tasks_tomorrow, last_seen_msg, last_typing, last_action, created_at) VALUES (NULL, 'Admin', '$ADMIN_EMAIL', '$hashed_password', NULL, 1, NULL, NULL, 1, 1, 1, 1, NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);"

    echo "Admin user created. Username: $ADMIN_EMAIL. Password: **HIDDEN**"
}

set_apache_server_name() {
    if [ -n "$APACHE_SERVER_NAME" ]; then
        echo "ServerName $APACHE_SERVER_NAME" >> /etc/apache2/apache2.conf;
    fi
}

# Function to check DB connection
check_db() {
    mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" -D "$DB_DATABASE" -N -s -e "SELECT 1;" > /dev/null 2>&1
}

# Function to wait for the database
wait_for_db() {
    local delay=5  # delay in seconds
    local attempt=1

    while ! check_db; do
        echo "Waiting for database to be available... Attempt $attempt"
        attempt=$((attempt+1))
        sleep "$delay"
    done

    echo "Database is available."
}

# Configure PHP error reporting
configure_php_error_reporting

# Create .env configuration file
create_environment_file

# To get rid of apache warnings, you can set the server name with the env var.
set_apache_server_name

# Call the wait_for_db function
wait_for_db

# Copy migration content
cp /tmp/migrations/* /var/www/html/app/migrations

# Set permissions to folder for migrations
chown -R www-data:www-data /var/www/html/app/migrations

# Show product version
php asatru product:version

# Run database migrations
if [ $(mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -P "$DB_PORT" -D "$DB_DATABASE" -N -s -e "SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$DB_DATABASE' AND TABLE_NAME = 'plants';") -eq 1 ]
then
    echo "Running unapplied database migrations..."
    php asatru migrate:list
    php asatru migrate:upgrade
else
    echo "Running full database migrations..."
    php asatru migrate:fresh
fi

# Check if app settings profile exists and create it if not.
add_initial_settings_if_missing

# Check if admin user exists and create it if not.
add_admin_user_if_missing

# Add default calendar classes if missing
php asatru calendar:classes

# Add default plant attributes if missing
php asatru plants:attributes

# Copy default images
cp /tmp/img/* /var/www/html/public/img

# Set permissions to folders for images
chown -R www-data:www-data /var/www/html/public/img
chmod 755 /var/www/html/public/img

# Set permissions to folder for logs
chown -R www-data:www-data /var/www/html/app/logs

# Set permissions to folder for backups
chown -R www-data:www-data /var/www/html/public/backup
chmod 755 /var/www/html/public/backup

# Copy themes content
cp -r /tmp/themes/* /var/www/html/public/themes

# Set permissions to folder for themes
chown -R www-data:www-data /var/www/html/public/themes

# Print informational message
echo -e "\033[32mThe system is now ready for operation.\033[39m"

# Then exec the container's main process (CMD)
exec "$@"
