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

# Moodle lives here
WORKDIR /var/www/html

# Copy Moodle source
COPY . /var/www/html

# Moodle data directory (backed by Railway volume)
RUN mkdir -p /var/www/moodledata \
 && chown -R www-data:www-data /var/www/html /var/www/moodledata

EXPOSE 80

# Custom entrypoint that fixes Apache MPMs on Railway (from official Station solution)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Use our script as the main command; ENTRYPOINT from php:8.1-apache stays the same
CMD ["/usr/local/bin/docker-entrypoint.sh"]
