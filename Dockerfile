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

# Ajouter les configs
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 8080

# Ajouter le script cron
COPY ./docker/crontab /etc/cron.d/laravel-cron
# Configurer la tâche cron pour exécuter le scheduler Laravel chaque minute
RUN echo '* * * * * /usr/local/bin/php /var/www/artisan schedule:run >> /dev/null 2>&1' > /etc/cron.d/laravel-cron \
    && chmod 0644 /etc/cron.d/laravel-cron \
    && crontab /etc/cron.d/laravel-cron


# Démarrer Nginx + PHP-FPM via supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]