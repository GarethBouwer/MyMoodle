# Moodle running on Apache + PHP 8.1 (supported)
FROM php:8.1-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libpq-dev \
    libldap2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd intl xml zip mbstring curl pdo_mysql pdo_pgsql ldap opcache

# Copy Moodle into the container
COPY . /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80
