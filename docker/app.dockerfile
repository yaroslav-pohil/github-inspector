FROM php:7.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    wget \
    unzip \
    && pecl install xdebug-3.1.3 \
    && docker-php-ext-install mysqli pdo_mysql mbstring \
    && docker-php-ext-enable xdebug mysqli

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === \"$(wget -q -O - https://composer.github.io/installer.sig)\") \
    { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } \
    echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN composer self-update --2

COPY xdebug.ini /usr/local/etc/php/conf.d/
