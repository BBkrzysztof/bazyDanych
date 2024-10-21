FROM php:8.1-apache

ENV APP_HOME=/var/www/html

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libicu-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache mbstring xml

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR $APP_HOME
COPY . $APP_HOME

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data $APP_HOME \
    && a2enmod rewrite

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# 8. Konfiguracja Xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 80
COPY ./apache-vhost.conf /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
