# 1) Image de base avec PHP-FPM
FROM php:8.2-fpm

# 2) Installer les dépendances système et l’extension PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev zip unzip git && \
    docker-php-ext-install pdo_pgsql

# 3) Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) Définir le répertoire de travail
WORKDIR /app

# 5) Copier et installer les dépendances PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# 6) Copier le code de l’application
COPY . .

# 7) Construire les assets et nettoyer le cache (optionnel selon ton setup)
RUN php bin/console cache:clear --env=prod

# 8) Lancer PHP-FPM
CMD ["php-fpm"]
