FROM php:8.5-cli

# Instalasi dependensi sistem, ekstensi PHP, dan Node.js
RUN apt-get update && apt-get install -y \
    git unzip zip curl ghostscript tesseract-ocr tesseract-ocr-ind \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip

# Salin Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Salin semua file proyek
COPY . .

# Instal dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Instal dependensi Node.js dan build Vite assets
RUN npm install && npm run build

# Berikan akses tulis untuk folder cache dan log Laravel
RUN chmod -R 775 storage bootstrap/cache public/build

# Gunakan format shell agar variabel $PORT terbaca sempurna
CMD sh -c "touch database/database.sqlite && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=$PORT"
