# php 8.1 image
FROM php:8.4-fpm-alpine

# development packages
RUN apt-get update && apt-get install -y \
    git \
    libbz2-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libpng-dev \
    libicu-dev \
    libonig-dev \
    libpq-dev \
    libzip-dev

# required php extensions
RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    mbstring \
    pdo \
    zip

# upload configuration
RUN echo "file_uploads = On\n" \
    "memory_limit = 256M\n" \
    "upload_max_filesize = 50M\n" \
    "post_max_size = 50M\n" \
    "max_execution_time = 60\n" \
    > /usr/local/etc/php/conf.d/uploads.ini

# composer 2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# xdebug installation and config
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY conf.d/* $PHP_INI_DIR/conf.d/