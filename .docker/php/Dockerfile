FROM php:8.3-fpm

ENV TIMEZONE=UTC

COPY .docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    procps \
    openssl \
    git \
    zlib1g-dev \
    libzip-dev \
    libicu-dev  \
    zip \
    unzip \
    libonig-dev \
	libmongoc-dev \
    acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN pecl install xdebug
COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d
RUN docker-php-ext-enable xdebug

RUN docker-php-ext-install \
    pdo pdo_mysql opcache mbstring

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
