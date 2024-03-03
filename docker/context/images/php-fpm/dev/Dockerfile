FROM php:8.2.13-fpm-alpine

RUN apk add --update --no-cache \
      zip git libpq-dev pv curl supervisor wget unzip

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/install-php-extensions
RUN install-php-extensions gd xdebug amqp apcu intl zip opcache pdo bcmath sockets redis pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ./conf/php-fpm/dev/php.ini /usr/local/etc/php/php.ini
COPY ./conf/php-fpm/dev/remote-xdebug.ini /usr/local/etc/php/remote-xdebug.ini
COPY ./conf/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

### timezone ###
ARG TZ="Europe/Moscow"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

### permissions ###
RUN mkdir -p /var/www/var/cache /var/www/var/log && chmod -R 777 /var/www/var
WORKDIR /var/www/