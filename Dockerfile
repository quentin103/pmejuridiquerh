FROM php:8.2-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif bcmath

# Active mod_rewrite
RUN a2enmod rewrite

# Définir DocumentRoot vers public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory /var/www/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# 📦 Copier TOUT le projet Laravel dans l’image
COPY . /var/www

# Définir le dossier de travail
WORKDIR /var/www
RUN chmod -R 777 storage bootstrap/cache