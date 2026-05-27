FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        intl \
        zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

RUN sed -ri -e 's!/var/www/html!/app/web!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/app/web!g' /etc/apache2/apache2.conf \
    && a2enmod rewrite
