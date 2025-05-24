FROM php:8.2-apache

# Installer les extensions PHP standards qu’on trouve sur les mutualisés
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif bcmath

# Activer mod_rewrite, indispensable pour Laravel
RUN a2enmod rewrite

# Changer le DocumentRoot pour qu’Apache serve directement le dossier public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Ajouter les permissions pour que .htaccess fonctionne
RUN echo '<Directory /var/www/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www