FROM php:8.1-apache

# Enable Apache rewrite for clean URLs (recommended by Moodle)
RUN a2enmod rewrite

# Install system deps needed for Moodle + PostgreSQL extensions
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
 && rm -rf /var/lib/apt/lists/*

# Web root
WORKDIR /var/www/html

# Copy Moodle source
COPY . /var/www/html

# Moodle data directory (Railway volume mounts here)
RUN mkdir -p /var/www/moodledata \
 && chown -R www-data:www-data /var/www/html /var/www/moodledata

EXPOSE 80
