FROM php:apache

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN rm composer-setup.php

RUN docker-php-ext-install pdo_mysql

RUN a2enmod rewrite
