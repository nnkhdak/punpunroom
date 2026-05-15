FROM php:8.3-apache-bookworm

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    zlib1g-dev \
    unzip \
    locales \
    vim \
    libaio1 \
    libaio-dev \
    libncurses5-dev \
    && apt-get clean

RUN docker-php-ext-install \
    # ctype \
    # curl \
    # dom \
    # fileinfo \
    # filter \
    # hash \
    # intl \
    # json \
    mbstring \
    # openssl \
    # pcre \
    # PDO \
    pdo \
    pdo_mysql \
    # pdo_sqlite \
    # session \
    # tokenizer \
    # xml \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN a2enmod rewrite headers authz_groupfile

COPY php.ini /usr/local/etc/php/php.ini
COPY openssl.cnf /etc/ssl/openssl.cnf
COPY apache2.conf /etc/apache2/apache2.conf
COPY api.conf /etc/apache2/conf-available/api.conf
RUN a2enconf api

WORKDIR /var/www

EXPOSE 80
