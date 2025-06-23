FROM php:8.1-cli

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
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libcurl4-openssl-dev \
    ca-certificates \
    curl \
    subversion \
 && docker-php-ext-configure gd \
      --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
 && docker-php-ext-install \
      pdo_pgsql zip intl mbstring xml opcache gd curl

# Définir la variable d’environnement pour autoriser l'exécution en root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installer Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

# Déboguer si Composer est bien installé
RUN composer --version
RUN composer clear-cache
RUN composer install --optimize-autoloader && composer dump-autoload --no-dev

# Permissions (optionnel mais utile)
RUN chown -R www-data:www-data /app
USER www-data

# Commande par défaut
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
