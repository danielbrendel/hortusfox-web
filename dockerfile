FROM php:8.2-apache-bookworm


COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN rm /etc/apt/preferences.d/no-debian-php
RUN apt-get update && apt-get install -y \
    unzip\
    php-zip\
    git\
    php-mysql \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    default-mysql-client && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

# Configure and install GD
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install gd

# Copy content

# Copy from tagged release, useful for CI/CD
#ENV HF_VERSION 1.6
#RUN curl -SSL https://github.com/danielbrendel/hortusfox-web/archive/refs/tags/v$HF_VERSION.tar.gz \
#    | tar -v --strip-components=1 -C /var/www/html -xz

# Copy from master branch
#RUN curl -SSL https://github.com/danielbrendel/hortusfox-web/tarball/master \
#    | tar -v --strip-components=1 -C /var/www/html -xz

COPY . /var/www/html

# copy default files in /public/img so they can be copied if needed in entrypoint
RUN mkdir /tmp/img
RUN cp /var/www/html/public/img/* /tmp/img
VOLUME /var/www/html/public/img

# Create volume for logs
VOLUME /var/www/html/app/logs

# copy PHP config
RUN cp /var/www/html/99-php.ini /usr/local/etc/php/conf.d/

# install deps
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN cd /var/www/html &&  /usr/local/bin/composer install

# Optimize the autoloader
RUN composer dump-autoload --optimize

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

EXPOSE 80

# Copy docker-entrypoint.sh into the container
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set the script as the entrypoint
WORKDIR /var/www/html
ENTRYPOINT ["docker-entrypoint.sh"]

# Start Apache server (CMD)
CMD ["apache2-foreground"]