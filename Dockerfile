FROM php:8.0-fpm-alpine

RUN apk add --no-cache --virtual .deps \
    git \
    icu-libs \
    zlib \
    openssh \
    libxslt

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    icu-dev \
    zlib-dev \
    libzip-dev \
    gmp-dev \
    libxml2-dev \
    && docker-php-ext-install -j$(nproc) \
    intl \
    pdo \
    soap \
    pcntl \
    exif \
    pdo_mysql \
    zip \
    && pecl install \
    redis \
    && docker-php-ext-enable --ini-name 20-redis.ini redis

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
