FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo mysqli pdo_mysql pdo_pgsql

COPY --from=composer /usr/bin/composer /usr/bin/composer

# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     zip \
#     vim \
#     unzip
