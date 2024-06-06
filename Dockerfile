# Usa la imagen oficial de PHP
FROM php:8.1-fpm

# Instala extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Instala extensiones adicionales de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define el directorio de trabajo
WORKDIR /var/www

# Copia el composer.json y el composer.lock y ejecuta composer install
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-progress --no-interaction

# Copia el resto de los archivos de la aplicación
COPY . .

# Asigna permisos a las carpetas de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Ejecuta el servidor PHP-FPM
CMD ["php-fpm"]
