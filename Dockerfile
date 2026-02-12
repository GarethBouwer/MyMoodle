FROM php:8.3-apache

# Enable Apache rewrite (Moodle needs this)
RUN a2enmod rewrite

# System deps + PHP extensions required by Moodle 5.x
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

# Moodle code root
WORKDIR /var/www/html

# Copy Moodle source
COPY . /var/www/html

# Install Composer (official installer) and Moodle vendor deps
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && rm composer-setup.php \
 && composer install --no-dev --classmap-authoritative --no-interaction --prefer-dist

# moodledata (Railway volume mounts here)
RUN mkdir -p /var/www/moodledata \
 && chown -R www-data:www-data /var/www/html /var/www/moodledata

# Apache vhost: serve ONLY /public (Moodle 5.x requirement) and set ServerName
RUN printf '%s\n' \
    '<VirtualHost *:80>' \
    '    ServerName clementsandcox.academy' \
    '    ServerAlias www.clementsandcox.academy' \
    '    DocumentRoot /var/www/html/public' \
    '    <Directory /var/www/html/public>' \
    '        AllowOverride All' \
    '        Require all granted' \
    '    </Directory>' \
    '</VirtualHost>' \
    > /etc/apache2/sites-available/000-default.conf

# Router + PHP max_input_vars via .htaccess in /public
RUN printf '%s\n' \
    'php_value max_input_vars 5000' \
    'FallbackResource /r.php' \
    >> /var/www/html/public/.htaccess

EXPOSE 80
