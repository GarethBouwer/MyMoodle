FROM php:8.1-apache

# Install Moodle PHP dependencies
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
 && docker-php-ext-install gd intl xml zip mbstring curl pdo_pgsql opcache \
 && rm -rf /var/lib/apt/lists/*

# Web root
WORKDIR /var/www/html

# Copy Moodle source
COPY . /var/www/html

# Moodle data directory (your Railway volume mounts here)
RUN mkdir -p /var/www/moodledata \
 && chown -R www-data:www-data /var/www/html /var/www/moodledata

EXPOSE 80
