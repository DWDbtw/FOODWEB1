# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Устанавливаем расширение PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Включаем mod_rewrite
RUN a2enmod rewrite

# Копируем файлы проекта
COPY . /var/www/html/

# Устанавливаем права
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Включаем отображение ошибок для отладки
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini

EXPOSE 80
