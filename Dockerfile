FROM php:8.1-apache

LABEL authors="CHRISTIAN AKESSE"

# Installe les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    cron \
    vim

# Installe les extensions PHP nécessaires à Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip mbstring exif pcntl bcmath

# Active mod_rewrite
RUN a2enmod rewrite

# Crée le dossier de travail
WORKDIR /var/www/html

# Copie ton projet Laravel (avec vendor, .env, etc.)
COPY . /var/www/html

# Fixe les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configuration Apache (facultatif, si tu veux une config custom)
COPY ./docker/apache/laravel.conf /etc/apache2/sites-enabled/000-default.conf

# Pas besoin de CMD personnalisé, Apache se lance automatiquement