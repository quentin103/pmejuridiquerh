# Utilise une image PHP avec Apache
FROM php:8.2-apache

# Installe les extensions nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Active le mod_rewrite (nécessaire pour Laravel)
RUN a2enmod rewrite

# Copie la config Apache custom (voir plus bas)
COPY ./docker/apache/laravel.conf /etc/apache2/sites-available/000-default.conf

# Copie tous les fichiers Laravel
COPY . /var/www/html

# Donne les bons droits à Apache sur storage et cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Point d'entrée
CMD ["apache2-foreground"]