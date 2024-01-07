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
        echo 'error_reporting = E_ALL' > /usr/local/etc/php/conf.d/errors.ini
        echo 'display_errors = On' >> /usr/local/etc/php/conf.d/errors.ini
    fi
}

# Function to check if the admin user exists
check_admin_user_exists() {
    local user_count=$(mysql -u "$DB_USERNAME" -p"$DB_PASSWORD" -h "$DB_HOST" -D "$DB_DATABASE" -N -s -e "SELECT COUNT(*) FROM users WHERE email='$ADMIN_EMAIL';")
    if [[ $user_count -gt 0 ]]; then
        return 0
    else
        return 1
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

# Configure PHP error reporting
configure_php_error_reporting

# Run database migrations
echo "Running database migrations..."
php asatru migrate:fresh

# Check if admin user exists
if ! check_admin_user_exists; then
    echo "Admin user ($ADMIN_EMAIL) does not exist. Creating..."
    create_admin_user
else
    echo "Admin user ($ADMIN_EMAIL) already exists. Skipping user creation."
fi

# Then exec the container's main process (CMD)
exec "$@"
