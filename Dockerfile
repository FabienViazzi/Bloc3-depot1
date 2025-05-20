# 1. Choisir une image PHP officielle
FROM php:8.1-cli

# 2. Installer les extensions nécessaires (pdo_pgsql, etc.) et Composer
RUN apt-get update \
  && apt-get install -y git unzip libpq-dev \
  && docker-php-ext-install pdo_pgsql \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 3. Copier le code et installer les dépendances
WORKDIR /app
COPY . /app
RUN composer install --no-dev --optimize-autoloader

# 4. Exposer par défaut le port 8080 (c’est interne à Docker)
EXPOSE 8080

# 5. Démarrer le serveur PHP intégré en écoutant sur 0.0.0.0 et sur $PORT
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
