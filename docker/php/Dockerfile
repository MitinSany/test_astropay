FROM php:7.1-fpm-alpine
WORKDIR /application

ENV XDEBUGINI_PATH=/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
ENV PHP_CUSTOM_SETTINGS_PATH=/usr/local/etc/php/conf.d/docker-php-custom-settings.ini

RUN apk update \
    && apk add nodejs \
    && apk add git \
    && apk add zlib-dev \
    && docker-php-ext-install -j "$(getconf _NPROCESSORS_ONLN)" pdo pdo_mysql mysqli mbstring zip \
    && apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
    && docker-php-source extract \
    && pecl install xdebug \
    && docker-php-source delete \
    && apk del $PHPIZE_DEPS \
    && apk del .phpize-deps \
    && docker-php-ext-enable xdebug zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && echo 'xdebug.remote_enable=on' >> $XDEBUGINI_PATH \
    && echo 'xdebug.remote_autostart=on' >> $XDEBUGINI_PATH \
    && echo 'xdebug.remote_mode=req' >> $XDEBUGINI_PATH \
    && echo 'xdebug.remote_port=9000' >> $XDEBUGINI_PATH \
    && echo 'xdebug.remote_host='`/sbin/ip route|awk '/default/ { print $3 }'` >> $XDEBUGINI_PATH \
    && echo 'memory_limit=256M' >> $PHP_CUSTOM_SETTINGS_PATH \
    && rm -rf /tmp/* \
    && rm -rf /var/cache/apk/*
