FROM php:8.2-fpm

# Gerekli paketleri yükle
RUN apt-get update && apt-get install -y \
    libpq-dev unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

# Composer yükleme
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Bağımlılıkları yükle
RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
