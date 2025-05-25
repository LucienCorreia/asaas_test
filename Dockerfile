FROM  --platform=$BUILDPLATFORM dunglas/frankenphp:php8.4-alpine

RUN apk update

RUN apk add --no-cache \
    libxml2-dev \
    libzip-dev \
    icu-dev \
    zlib-dev \
    libxml2 \
    libzip \
    zlib \
    zip \
    unzip \
    composer \
    php-exif \
    php-session \
    php-tokenizer \
    php-xml \
    php-gd \
    php-dom \
    php-fileinfo \
    php-simplexml \
    php-xmlwriter \
    php-exif \
    autoconf \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install intl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install xml

WORKDIR /app

RUN chown -R www-data:www-data /app

USER www-data

COPY laravel /app

USER root

RUN composer install --no-interaction --optimize-autoloader

RUN chmod 777 -R /app/storage/framework/views
RUN chmod 777 -R /app/storage/framework/cache
RUN chmod 777 -R /app/storage/framework/sessions
RUN chmod 777 -R /app/storage/logs
RUN chmod 777 -R /app/bootstrap/cache
RUN chmod 777 -R /data/caddy
RUN chmod 777 -R /config/caddy
RUN chmod 777 -R /app/.env

USER www-data

EXPOSE 80

CMD [ "frankenphp", "run" ]