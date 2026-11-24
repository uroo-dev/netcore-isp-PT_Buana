FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    curl \
    unzip

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    xml \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

CMD ["php-fpm"]
