FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install pdo_pgsql zip gd

COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
