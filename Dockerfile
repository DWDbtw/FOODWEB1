FROM php:8.2-apache

COPY . /var/www/html/

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite

ENV PORT=10000
EXPOSE 10000

RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf \
    && sed -i 's/80/${PORT}/g' /etc/apache2/sites-enabled/000-default.conf || true
