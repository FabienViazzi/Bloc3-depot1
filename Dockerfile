FROM php:8.2-fpm

# [1] Installer les dépendances système pour PostgreSQL, unzip, zip…
RUN apt-get update \
 && apt-get install -y libpq-dev unzip zip git \
 && docker-php-ext-install pdo pdo_pgsql

# [2] Récupérer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# [3] Copier seulement composer.* pour profiter du cache Docker
COPY composer.json composer.lock ./

# [4] Autoriser l’exécution des scripts Composer en root
ENV COMPOSER_ALLOW_SUPERUSER=1

# [5] Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# [6] Copier le reste du code
COPY . .

# [7] Vider le cache en prod
RUN php bin/console cache:clear --env=prod

# [8] Définir le point d’entrée (par exemple PHP-FPM)
CMD ["php-fpm"]
