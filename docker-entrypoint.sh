#!/bin/bash

# Default values for admin user and password
DEFAULT_ADMIN_EMAIL="admin@example.com"
DEFAULT_ADMIN_PASSWORD=$(openssl rand -base64 12)  # Generate a random password

# Use environment variables if provided, otherwise use defaults
ADMIN_EMAIL="${HORTUSFOX_ADMIN:-$DEFAULT_ADMIN_EMAIL}"
ADMIN_PASSWORD="${HORTUSFOX_PASSWORD:-$DEFAULT_ADMIN_PASSWORD}"

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
    # Remove if already exists
    rm -f "/var/www/html/.env"

    # App settings
    echo "APP_NAME=\"HortusFox\"" >> /var/www/html/.env
    echo "APP_DEBUG=$APP_DEBUG" >> /var/www/html/.env
    echo "APP_BASEDIR=\"\"" >> /var/www/html/.env
    echo "APP_LANG=\"en\"" >> /var/www/html/.env
    echo "APP_WORKSPACE=\"$APP_WORKSPACE\"" >> /var/www/html/.env
    echo "APP_ENABLESCROLLER=true" >> /var/www/html/.env
    echo "APP_OVERLAYALPHA=null" >> /var/www/html/.env
    echo "APP_ENABLECHAT=true" >> /var/www/html/.env
    echo "APP_ONLINEMINUTELIMIT=5" >> /var/www/html/.env
    echo "APP_SHOWCHATONLINEUSERS=false" >> /var/www/html/.env
    echo "APP_SHOWCHATTYPINGINDICATOR=false" >> /var/www/html/.env
    echo "APP_OVERDUETASK_HOURS=10" >> /var/www/html/.env
    echo "APP_CRONPW=null" >> /var/www/html/.env
    echo "APP_CRONJOB_MAILLIMIT=5" >> /var/www/html/.env
    echo "APP_GITHUB_URL=\"https://github.com/danielbrendel/hortusfox-web\"" >> /var/www/html/.env
    echo "APP_SERVICE_URL=\"https://www.hortusfox.com\"" >> /var/www/html/.env
    echo "APP_ENABLEHISTORY=true" >> /var/www/html/.env
    echo "APP_HISTORY_NAME=\"History\"" >> /var/www/html/.env

    # Session
    echo "SESSION_ENABLE=true" >> /var/www/html/.env
    echo "SESSION_DURATION=31536000" >> /var/www/html/.env
    echo "SESSION_NAME=null" >> /var/www/html/.env

    # Photo resize factors
    echo "PHOTO_RESIZE_FACTOR_DEFAULT=1.0" >> /var/www/html/.env
    echo "PHOTO_RESIZE_FACTOR_1=0.5" >> /var/www/html/.env
    echo "PHOTO_RESIZE_FACTOR_2=0.4" >> /var/www/html/.env
    echo "PHOTO_RESIZE_FACTOR_3=0.4" >> /var/www/html/.env
    echo "PHOTO_RESIZE_FACTOR_4=0.3" >> /var/www/html/.env
    echo "PHOTO_RESIZE_FACTOR_5=0.2" >> /var/www/html/.env

    # Database settings
    echo "DB_ENABLE=true" >> /var/www/html/.env
    echo "DB_HOST=$DB_HOST" >> /var/www/html/.env
    echo "DB_USER=$DB_USERNAME" >> /var/www/html/.env
    echo "DB_PASSWORD=\"$DB_PASSWORD\"" >> /var/www/html/.env
    echo "DB_PORT=3306" >> /var/www/html/.env
    echo "DB_DATABASE=$DB_DATABASE" >> /var/www/html/.env
    echo "DB_DRIVER=mysql" >> /var/www/html/.env
    echo "DB_CHARSET=\"$DB_CHARSET\"" >> /var/www/html/.env

    # SMTP settings
    echo "SMTP_FROMNAME=\"$SMTP_FROMNAME\"" >> /var/www/html/.env
    echo "SMTP_FROMADDRESS=\"$SMTP_FROMADDRESS\"" >> /var/www/html/.env
    echo "SMTP_HOST=\"$SMTP_HOST\"" >> /var/www/html/.env
    echo "SMTP_PORT=$SMTP_PORT" >> /var/www/html/.env
    echo "SMTP_USERNAME=\"$SMTP_USERNAME\"" >> /var/www/html/.env
    echo "SMTP_PASSWORD=\"$SMTP_PASSWORD\"" >> /var/www/html/.env
    echo "SMTP_ENCRYPTION=tls" >> /var/www/html/.env

    # Logging
    echo "LOG_ENABLE=false" >> /var/www/html/.env
}

# Function to check if the admin user exists
add_admin_user_if_missing() {
    local user_count=$(mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -D "$DB_DATABASE" -N -s -e "SELECT COUNT(*) FROM users WHERE email='$ADMIN_EMAIL';")
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
    mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -D "$DB_DATABASE" -e "INSERT INTO users (id, name, email, password, password_reset, session, status, admin, lang, chatcolor, show_log, show_plants_aoru, notify_tasks_overdue, notify_tasks_tomorrow, last_seen_msg, last_typing, last_action, created_at) VALUES (NULL, 'Admin', '$ADMIN_EMAIL', '$hashed_password', NULL, NULL, 0, 1, NULL, NULL, 1, 1, 1, 1, NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);"

    echo "Admin user created. Username: $ADMIN_EMAIL, Password: $ADMIN_PASSWORD"
}

set_apache_server_name() {
    if [ -n "$APACHE_SERVER_NAME" ]; then
        echo "ServerName $APACHE_SERVER_NAME" >> /etc/apache2/apache2.conf;
    fi
}

# Function to check DB connection
check_db() {
    mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -D "$DB_DATABASE" -N -s -e "SELECT 1;" > /dev/null 2>&1
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

# Run database migrations
echo "Running database migrations..."
php asatru migrate:fresh

# Check if admin user exists and create it if not.
add_admin_user_if_missing

# Set permissions to folders for file upload
chown -R www-data:www-data /var/www/html/public/img
chmod -R 755 /var/www/html/public/img

# Then exec the container's main process (CMD)
exec "$@"
