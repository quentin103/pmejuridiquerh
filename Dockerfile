FROM php:8.2-apache

# Active mod_rewrite (nécessaire pour Laravel)
RUN a2enmod rewrite

# Change le doc root vers /public
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Mise à jour du vhost pour qu'il serve le dossier public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Copie tout ton projet Laravel dans le container
COPY . /var/www/html

# Fix des permissions pour Laravel (optionnel si ton CMS n’utilise pas de stockage local)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# C'est Apache qui lance tout
CMD ["apache2-foreground"]