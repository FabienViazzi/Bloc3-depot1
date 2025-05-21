# Fichier Dockerfile

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
    subversion \
 && docker-php-ext-configure gd \
      --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
 && docker-php-ext-install \
      pdo_pgsql zip intl mbstring xml opcache gd curl


RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app


COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader


COPY . .


EXPOSE 8000


ENV PORT 8000
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t public"]
