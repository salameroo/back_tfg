# Usa la imagen oficial de PHP
FROM php:8.1-fpm

# Establecer el directorio de trabajo
WORKDIR /var/www

# Instalar dependencias del sistema
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
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev
# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el archivo composer.json y composer.lock
COPY composer.json composer.lock /var/www/

# Instalar dependencias de Composer
RUN composer install --no-scripts --no-autoloader

# Copiar el resto de la aplicación
COPY . /var/www

# Instalar dependencias de Composer y construir la aplicación
RUN composer install

# Copiar el archivo de configuración de PHP
COPY ./docker/php.ini /usr/local/etc/php/

# Establecer los permisos adecuados
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Exponer el puerto 9000
EXPOSE 9000

# Comando por defecto
CMD ["php-fpm"]