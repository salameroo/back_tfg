# Usa una imagen base de PHP 8.1 con FPM
FROM php:8.1-fpm

# Instala extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el código fuente
COPY . .

# Instala dependencias de Composer
RUN composer install

# Establece permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Expone el puerto 9000 para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
