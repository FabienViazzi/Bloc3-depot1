# 1. Choisir une image PHP officielle
FROM php:8.1-cli

# 2. Installer les paquets système et extensions PHP nécessaires
RUN apt-get update \
 && apt-get install -y \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
 && docker-php-ext-install \
    pdo_pgsql \
    zip

# 3. Installer Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && rm composer-setup.php

# 4. Définir le répertoire de travail
WORKDIR /app

# 5. Copier les fichiers de dépendances d'abord (pour profiter du cache)
COPY composer.json composer.lock ./

# 6. Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 7. Copier le reste de votre code
COPY . .

# 8. Exposer le port interne (par défaut 8080)
EXPOSE 8080

# 9. Démarrage du serveur Symfony en mode prod, écoutant sur 0.0.0.0 et sur le port $PORT
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
