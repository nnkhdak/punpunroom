FROM php:8.3-apache

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

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Oracle Instant Clientのインストール
ENV ORACLE_VER=19
ENV ORACLE_HOME=/usr/local/instantclient
ENV LD_LIBRARY_PATH=$ORACLE_HOME:$LD_LIBRARY_PATH
ENV OCI_LIB_DIR $ORACLE_HOME
ENV OCI_INC_DIR $ORACLE_HOME/sdk/include
COPY bin/*$ORACLE_VER*.zip /tmp/
RUN ARCH=$(uname -m) && if [ "$ARCH" = "x86_64" ]; then rm /tmp/*arm64*; elif [ "$ARCH" = "aarch64" ]; then rm /tmp/*x64*; fi
RUN mkdir -p /tmp/instantclient \
&& unzip -o /tmp/instantclient-basic-*.zip -d /tmp/instantclient \
&& unzip -o /tmp/instantclient-sdk-*.zip   -d /tmp/instantclient \
&& mv /tmp/instantclient/*$ORACLE_VER* $ORACLE_HOME \
&& docker-php-ext-configure oci8 --with-oci8=instantclient,$ORACLE_HOME \
&& docker-php-ext-install oci8 \
&& docker-php-ext-enable oci8 \
&& docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,$ORACLE_HOME,$ORACLE_VER \
&& docker-php-ext-install pdo_oci \
&& docker-php-ext-enable pdo_oci \
&& rm -f /tmp/*.zip \
&& rm -rf /tmp/instantclient/

#   COPY api.conf /etc/apache2/conf-available/

WORKDIR /var/www

EXPOSE 80
