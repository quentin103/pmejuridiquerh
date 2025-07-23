FROM php:8.2-fpm-alpine

# Installer les extensions nécessaires à Laravel + dcron
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
    dcron \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl intl

# Copier le projet Laravel (avec /vendor déjà présent)
COPY . /var/www
WORKDIR /var/www 

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Copier la configuration crontab
COPY ./docker/crontab /etc/crontabs/root

# Donner les bonnes permissions aux fichiers cron
RUN chmod 0644 /etc/crontabs/root

# Ajouter les configs
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 8080

# Démarrer Nginx + PHP-FPM + Cron via supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]