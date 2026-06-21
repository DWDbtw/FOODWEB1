FROM php:8.2-apache

COPY . /var/www/html/

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

ENV PORT 10000
EXPOSE 10000

RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf