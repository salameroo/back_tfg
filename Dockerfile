#  Defining the base image for our project, if you understand how docker images and layers work, this should not be difficult to understand. 
FROM php:8.1-cli
# We need to update the  image and install some import packages
RUN apt-get update -y && apt-get install -y openssl zip unzip git curl libpng-dev libonig-dev libxml2-dev
# cleaning packages and install scripts
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Installing composer which is used to install Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin –filename=composer
#Creating a configuration file for apache and linking
ADD 000-default.conf /etc/apache2/sites-available/
RUN ln -sf /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/000-default.conf 

#Restarting Apache
RUN a2enmod rewrite
RUN service apache2 restart
# Create a work directory and copy all project file into the 
WORKDIR /var/www/app/
COPY . /var/www/app
#Granting permissions to files and folders
RUN chmod -R o+w /var/www/app/storage
RUN chown -R www-data:www-data ./storage
RUN chgrp -R www-data storage bootstrap/cache
RUN chmod -R ug+rwx storage bootstrap/cache
RUN chmod -R 755 /var/www/app/
RUN find /var/www/app/ -type d -exec chmod 775 {} \;
RUN chown -R www-data:www-data /var/www
# Installing dependencies from laravel package 
RUN composer install --no-scripts --no-autoloader --no-ansi --no-interaction --working-dir=/var/www/app
#Running some packages 
RUN docker-php-ext-install mbstring pdo pdo_mysql mbstring exif pcntl bcmath gd opcache
#Running Laravel on docker, because we are using the php-7.3-cli so we have to use a php server in our docker image
CMD php artisan serve --host=0.0.0.0 --port=80
EXPOSE 80