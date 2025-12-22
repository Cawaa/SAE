# Utilisation de la version PHP 8.4 (celle affichée par votre erreur)
FROM php:8.4-apache AS builder

# Installation des dépendances système nécessaires
RUN apt-get update && apt-get install -y -q \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    vim \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installation et activation propre des extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl mysqli pdo_mysql mbstring gd zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configuration Apache
ADD data/ci.conf data/default.conf /etc/apache2/sites-available/
ADD data/ports.conf /etc/apache2/ports.conf

RUN a2enmod rewrite \
    && a2dissite 000-default \
    && a2ensite ci.conf default.conf

# Configuration PHP pour le développement
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

WORKDIR /var/www/html/CI4

CMD ["apache2-foreground"]