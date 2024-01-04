FROM php:8.2-apache

RUN a2enmod deflate expires rewrite
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions opcache intl gd exif imagick mysqli pdo_mysql redis xdebug

WORKDIR /var/www/html

EXPOSE 80