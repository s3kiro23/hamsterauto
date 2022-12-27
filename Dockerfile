#Install & config VHOST on apache server
FROM php:7.4-apache

ENV COMPOSER_ALLOW_SUPERUSER=1

# Soft for all others Docker & Composer
RUN apt-get update -qq && \
    apt-get install -qy  \
    curl  \
    nano \
    openssl \
    git  \
    wget  \
    unzip  \
    build-essential  \
    locales  \
    locales-all \
    software-properties-common && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Set Locale fr_FR
ENV LC_ALL fr_FR.UTF-8
ENV LANG fr_FR.UTF-8
ENV LANGUAGE fr_FR.UTF-8

## PHP Extensions

# intl
RUN apt-get update \
	&& apt-get install -y libicu-dev \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install -j$(nproc) intl

# xml
RUN apt-get update \
	&& apt-get install -y \
	libxml2-dev \
	libxslt-dev \
	&& docker-php-ext-install -j$(nproc) \
		dom \
		xmlrpc \
		xsl

# images
RUN apt-get update \
	&& apt-get install -y \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	libgd-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) \
		gd \
		exif

# database
RUN docker-php-ext-install -j$(nproc) \
	mysqli \
	pdo \
	pdo_mysql

# strings
RUN apt-get update \
    && apt-get install -y libonig-dev \
    && docker-php-ext-install -j$(nproc) \
	    gettext \
	    mbstring

# others
RUN docker-php-ext-install -j$(nproc) \
	soap \
	sockets

## NodeJS, NPM
# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && apt-get install -y nodejs

# Clean
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY config/php.ini "$PHP_INI_DIR/php.ini"

#Apache config
COPY config/hamsterauto.conf /etc/apache2/sites-available/hamsterauto.conf
RUN a2ensite hamsterauto.conf
RUN mkdir /var/www/hamsterauto
COPY ./app/ /var/www/hamsterauto

#Add rights & install link to between db & php
RUN chown -R www-data:www-data /var/www/hamsterauto

#Set Timezone in docker ENV
ENV TZ Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#Set default project directory
WORKDIR /var/www/hamsterauto

#Install dependencies
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader

# Finish composer
RUN composer dump-autoload --no-scripts --no-dev --optimize

