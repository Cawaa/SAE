# syntax=docker/dockerfile:1.4
ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-apache as builder

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libpng-dev \
    libsqlite3-dev \
    sqlite3 \
    unzip \
    vim \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    intl \
    mbstring \
    mysqli \
    pdo_mysql \
    pdo_sqlite \
    curl \
    gd \
    opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD data/app_php/data/ci.conf data/app_php/data/default.conf /etc/apache2/sites-available/
ADD data/app_php/data/ports.conf /etc/apache2/

RUN a2dissite 000-default.conf && \
    a2ensite ci.conf && \
    a2ensite default.conf && \
    a2enmod rewrite

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# PHP limits
COPY data/app_php/php-uploads.ini /usr/local/etc/php/conf.d/99-uploads.ini

COPY data/CI4 /var/www/html/CI4

# init script
COPY data/app_php/init-ci4.sh /usr/local/bin/init-ci4.sh
RUN chmod +x /usr/local/bin/init-ci4.sh

CMD ["apache2-foreground"]
