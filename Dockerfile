#Install PHP and required extensions
FROM php:7.4-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

ARG APP_NAME="hamsterauto"
RUN mkdir -p /opt/$APP_NAME

# Install required packages and extensions
RUN apt-get update -qq && \
    apt-get install -qy  \
    curl  \
    nano \
    locales  \
    locales-all \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set Locale fr_FR
ENV LC_ALL fr_FR.UTF-8
ENV LANG fr_FR.UTF-8
ENV LANGUAGE fr_FR.UTF-8

# Clean
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY tools/php.ini "$PHP_INI_DIR/php.ini"

#Set Timezone in docker ENV
ENV TZ Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#Set default project directory
COPY ./app/ /opt/$APP_NAME
RUN chown -R www-data:www-data /opt/$APP_NAME
WORKDIR /opt/$APP_NAME

#Install dependencies
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader

# Finish composer
RUN composer dump-autoload --no-scripts --no-dev --optimize