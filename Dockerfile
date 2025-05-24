FROM php:8.2-apache

# Installe les extensions nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif bcmath gd

# Active mod_rewrite
RUN a2enmod rewrite

# Modifie le DocumentRoot d’Apache pour qu’il pointe vers /var/www/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Ajoute aussi les droits d'accès dans la conf Apache (AllowOverride)
RUN echo "<Directory /var/www/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www