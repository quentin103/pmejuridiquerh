FROM php:8.2-fpm

# Installer les extensions PHP requises par Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Définir le dossier de travail
WORKDIR /var/www

# Copier tout le projet (inclut vendor, .env, etc.)
COPY . .

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exposer le port utilisé par PHP-FPM
EXPOSE 9000

# Lancer PHP-FPM
CMD ["php-fpm"]