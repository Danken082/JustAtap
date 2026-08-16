FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data \
    /var/www/storage \
    /var/www/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 10000

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]