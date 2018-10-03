FROM php:7.2-fpm

RUN apt-get update && apt-get install -y openssl git unzip

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo 'alias sf3="php bin/console"' >> ~/.bashrc

WORKDIR /var/www/jobs

RUN mkdir /var/cache/prod
RUN mkdir /var/logs
RUN mkdir /var/sessions
RUN chown -R www-data:www-data /var/cache
RUN chown -R www-data:www-data /var/logs
RUN chown -R www-data:www-data /var/sessions
