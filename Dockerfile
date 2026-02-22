FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip nodejs npm \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

EXPOSE 8080

CMD php artisan migrate --force && \
    php artisan config:clear && \
    php artisan view:clear && \
    php artisan serve --host 0.0.0.0 --port ${PORT:-8080}