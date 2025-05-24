FROM php:8.2-apache

# Installe juste les extensions PHP nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Active mod_rewrite pour les routes Laravel
RUN a2enmod rewrite

# Copie ta config Apache pour pointer vers /public
COPY ./docker/apache/laravel.conf /etc/apache2/sites-available/000-default.conf

# Copie l'app Laravel (déjà prête, avec vendor, .env, etc.)
COPY . /var/www/html

# Fix les permissions Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["apache2-foreground"]