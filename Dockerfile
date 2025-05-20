# Utilise l’image PHP FPM officielle
FROM php:8.2-fpm

# 1) Installer les dépendances système (PostgreSQL, unzip, zip, git)
RUN apt-get update \
 && apt-get install -y libpq-dev unzip zip git \
 && docker-php-ext-install pdo pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

# 2) Copier Composer depuis l’image officielle composer:2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3) Définir le répertoire de travail
WORKDIR /app

# 4) Copier uniquement les fichiers de configuration Composer pour profiter du cache Docker
COPY composer.json composer.lock ./

# 5) Autoriser l’exécution en root et installer les dépendances PHP sans lancer les scripts
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 6) Copier le reste de l’application
COPY . .

# 7) Vider le cache Symfony en mode production
RUN php bin/console cache:clear --env=prod

# 8) Point d’entrée : lance PHP-FPM
CMD ["php-fpm"]
