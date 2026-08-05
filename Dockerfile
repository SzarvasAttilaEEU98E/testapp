FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install \
        pdo_pgsql pgsql intl zip bcmath soap \
        &&pecl install redis \
        && docker-php-ext-enable redis \
        && -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . . 

RUN composer install --no-interaction

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
