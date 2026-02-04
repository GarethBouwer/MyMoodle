FROM php:8.1-apache

# Install Moodle PHP dependencies (Apache + mod_php already set up in this image)
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

# Copy Moodle into the container
COPY . /var/www/html

# Create moodledata and set permissions (Moodle requires this per docs)
RUN mkdir -p /var/www/moodledata \
 && chown -R www-data:www-data /var/www/html /var/www/moodledata

EXPOSE 80
