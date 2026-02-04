# Moodle running on Apache + PHP 8.1
FROM php:8.1-apache

# Make sure ONLY prefork MPM is enabled + enable rewrite
RUN a2dismod mpm_event mpm_worker || true \
 && a2enmod mpm_prefork rewrite

# Install dependencies (no extra apache packages here)
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
 && docker-php-ext-install gd intl xml zip mbstring curl pdo_pgsql ldap opcache \
 && rm -rf /var/lib/apt/lists/*

# Copy Moodle into the container
COPY . /var/www/html

# Create moodledata and fix permissions
RUN mkdir -p /var/www/moodledata \
 && chown -R www-data:www-data /var/www/html /var/www/moodledata

EXPOSE 80
