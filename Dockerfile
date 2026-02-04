FROM php:8.1-apache

# Enable Apache rewrite (required by Moodle for clean URLs)
RUN a2enmod rewrite

# Install system dependencies + PHP extensions needed by Moodle
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
 && docker-php-ext-install \
        gd \
        intl \
        xml \
        zip \
        mbstring \
        curl \
        pgsql \
        pdo_pgsql \
        opcache \
        ldap \
 && rm -rf /var/lib/apt/lists/*

# App root
WORKDIR /var/www/html

# Copy Moodle source into the image
COPY . /var/www/html

# Moodle data directory (Railway volume mounts here)
RUN mkdir -p /var/www/moodledata \
 && chown -R www-data:www-data /var/www/html /var/www/moodledata

# Configure Apache to serve only the /public directory (Moodle 5.x requirement)
RUN printf '%s\n' \
    '<VirtualHost *:80>' \
    '    ServerName localhost' \
    '    DocumentRoot /var/www/html/public' \
    '    <Directory /var/www/html/public>' \
    '        AllowOverride All' \
    '        Require all granted' \
    '    </Directory>' \
    '</VirtualHost>' \
    > /etc/apache2/sites-available/000-default.conf

EXPOSE 80
