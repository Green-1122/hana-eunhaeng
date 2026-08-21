FROM php:8.1-fpm

# Install system deps
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libssl-dev \
    zip \
    supervisor \
  && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl intl xml bcmath \
  && pecl install redis && docker-php-ext-enable redis \
  && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Ensure www-data owns the app
RUN chown -R www-data:www-data /var/www/html

USER www-data
