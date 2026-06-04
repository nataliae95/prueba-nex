FROM php:8.2-apache

# Sistema + extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    nano \
    curl \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    nodejs \
    npm \
    && docker-php-ext-install intl pdo pdo_mysql zip bcmath

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Habilita rewrite
RUN a2enmod rewrite

# Copia tu VirtualHost
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar la aplicación Laravel
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Comando para iniciar Apache en primer plano
CMD ["apache2-foreground"]