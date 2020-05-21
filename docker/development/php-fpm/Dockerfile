FROM php:7.4-fpm-alpine

RUN apk update && apk add unzip
RUN docker-php-ext-install pdo_mysql
RUN apk --no-cache add curl

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./conf.d /usr/local/etc/php/conf.d

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN apk add --update nodejs npm

WORKDIR /app