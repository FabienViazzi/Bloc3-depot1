# 1. Image de base PHP CLI
FROM php:8.1-cli

# 2. Paquets système + extensions PHP
RUN apt-get update \
 && apt-get install -y \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
 && docker-php-ext-install \
    pdo_pgsql \
    zip \
    intl \
    mbstring \
    xml \
    opcache

# 3. Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Copier uniquement les définitions de dépendances pour le cache
WORKDIR /app
COPY composer.json composer.lock ./

# 5. Lancer Composer en mode verbeux pour capturer l’erreur exacte
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist -vvv

# 6. Copier le reste de l’application
COPY . .

# 7. Exposer le port interne
EXPOSE 8080

# 8. Lancer le serveur PHP interne via Symfony
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
