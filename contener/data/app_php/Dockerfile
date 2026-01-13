# syntax=docker/dockerfile:1.4
ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-apache as builder

# 1. Installation des dépendances système
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

# 2. Installation et activation des extensions PHP
RUN docker-php-ext-install \
    intl \
    mbstring \
    mysqli \
    pdo_mysql \
    pdo_sqlite \
    curl \
    gd \
    opcache

# 3. Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Configuration Apache
# Les fichiers de conf doivent être dans data/app_php/data/
ADD data/ci.conf data/default.conf /etc/apache2/sites-available/
ADD data/ports.conf /etc/apache2/

RUN a2dissite 000-default.conf && \
    a2ensite ci.conf && \
    a2ensite default.conf && \
    a2enmod rewrite

# 5. Configuration PHP
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# PHP limits
COPY php-uploads.ini /usr/local/etc/php/conf.d/zz-php-uploads.ini


CMD ["apache2-foreground"]