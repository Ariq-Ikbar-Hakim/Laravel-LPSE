FROM php:8.5-cli

# Instalasi dependensi sistem dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip

# Salin Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Salin semua file proyek
COPY . .

# Instal dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Berikan akses tulis untuk folder cache dan log Laravel
RUN chmod -R 775 storage bootstrap/cache

# Hapus EXPOSE 8000 karena Railway mengaturnya secara dinamis
# Gunakan format JSON dengan sh -c agar variabel $PORT terbaca sempurna
CMD sh -c "php artisan serve --host=0.0.0.0 --port=$PORT"