FROM php:8.5-cli

# Instalasi dependensi sistem, ekstensi PHP, dan Node.js
RUN apt-get update && apt-get install -y \
    git unzip zip curl ghostscript tesseract-ocr tesseract-ocr-ind chromium \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev libmagickwand-dev imagemagick \
    && curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Salin Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENV PUPPETEER_SKIP_DOWNLOAD=true \
    CHROME_PATH=/usr/bin/chromium \
    CHROME_NO_SANDBOX=true

# Salin semua file proyek
COPY . .

# Instal dependensi Laravel
# Railway occasionally returns transient GitHub 504 responses while Composer
# downloads packages. Limit parallel downloads and retry the complete install.
RUN for attempt in 1 2 3; do \
        COMPOSER_MAX_PARALLEL_HTTP=1 composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader && exit 0; \
        echo "Composer install attempt ${attempt} failed; retrying..."; \
        sleep 5; \
    done; \
    exit 1

# Instal dependensi Node.js dan build Vite assets
RUN npm ci && npm run build

# Berikan akses tulis untuk folder cache dan log Laravel
RUN mkdir -p storage/app/public storage/logs storage/framework/cache/data storage/framework/sessions storage/framework/views \
    && chmod -R 775 storage bootstrap/cache public/build

RUN printf 'upload_max_filesize=20M\npost_max_size=22M\n' > /usr/local/etc/php/conf.d/uploads.ini

# Gunakan format shell agar variabel $PORT terbaca sempurna
CMD sh -c 'php artisan migrate --force && php artisan storage:link --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}'
