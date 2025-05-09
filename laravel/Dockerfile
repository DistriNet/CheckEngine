FROM php:8.4-fpm-bookworm

WORKDIR /var/www/html

RUN apt update -y &&\
    apt upgrade -y &&\
    apt install -y git libpq-dev zip &&\
    docker-php-ext-install pgsql pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

RUN composer global require laravel/installer

