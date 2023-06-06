FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . /app

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash \
      && apt-get install -y nodejs

RUN npm install

EXPOSE 9000

CMD ["php-fpm"]
