FROM php:8.4-fpm

# user and group id setup
ARG UID=1000
ARG GID=1000

RUN groupadd -g $GID myuser && \
    useradd -u $UID -g $GID -s /bin/bash -m myuser && \
    usermod -aG sudo myuser && \
    echo 'myuser:mypass' | chpasswd

# development packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    vim \
    curl \
    zip \
    libbz2-dev \
    libcurl4-openssl-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    libxml2-dev \
    libxslt-dev \
    libssl-dev \
    libwebp-dev \
    pkg-config \
    supervisor \
    cron \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# required php extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install \
        bcmath \
        bz2 \
        calendar \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo \
        pdo_pgsql \
        pgsql \
        soap \
        xsl \
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