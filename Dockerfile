FROM php:8.2-fpm-alpine

# Installer les extensions nécessaires à Laravel
RUN apk add --no-cache \
    nginx \
    bash \
    libzip-dev \
    oniguruma-dev \
    curl \
    icu-dev \
    zlib-dev \
    libxml2-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl intl

# Copier le projet Laravel (avec /vendor déjà présent)
COPY . /var/www
WORKDIR /var/www

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Ajouter les configs Nginx et Supervisor
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

# Copier la crontab personnalisée
COPY ./docker/crontab /etc/crontabs/root

# Donner les permissions nécessaires au script artisan
RUN chmod +x /var/www/artisan

# Créer le fichier de log pour cron
RUN touch /var/log/cron.log \
    && chown www-data:www-data /var/log/cron.log

EXPOSE 8080

# Lancer Nginx, PHP-FPM et cron via supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]