# Utilise l'image PHP avec Apache
FROM php:8.2-apache

# Installe les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif bcmath gd

# Active le module Apache mod_rewrite (important pour Laravel)
RUN a2enmod rewrite

# Installe Composer globalement
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définit le dossier de travail
WORKDIR /var/www

# Copie les fichiers du projet dans le conteneur (géré par docker-compose normalement)
# COPY . .

# Change les permissions (à ajuster si tu bosses en dev)
# RUN chown -R www-data:www-data /var/www

# Expose le port 80 (déjà fait par l’image apache, mais pour info)
EXPOSE 80