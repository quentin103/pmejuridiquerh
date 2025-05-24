FROM php:8.2-apache

# Active mod_rewrite pour Laravel
RUN a2enmod rewrite

# On définit /public comme racine du serveur Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# On applique ce changement dans la config Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Copie du projet Laravel dans le conteneur
COPY . /var/www/html

# Droits
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache