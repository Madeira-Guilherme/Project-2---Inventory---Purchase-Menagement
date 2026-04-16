FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libonig-dev \
    && docker-php-ext-install zip pdo pdo_mysql mbstring bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
