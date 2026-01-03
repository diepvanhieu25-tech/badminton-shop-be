FROM php:8.2-fpm

# 1. Cài các thư viện hệ thống cần thiết (QUAN TRỌNG: thêm jpeg và freetype)
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    libjpeg62-turbo-dev libfreetype6-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    # 2. Cấu hình GD để nhận diện JPEG (BẮT BUỘC để fix lỗi của bạn)
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 laravel
RUN useradd -u 1000 -ms /bin/bash -g laravel laravel

WORKDIR /var/www

COPY --chown=laravel:laravel . /var/www

USER laravel