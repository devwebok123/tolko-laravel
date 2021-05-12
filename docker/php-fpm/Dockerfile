FROM php:7.4-fpm

# Install Dependences
RUN apt-get update
RUN apt-get upgrade -yq
RUN apt-get install -yq apt-utils
RUN apt-get install -yq libpq-dev libmcrypt-dev libreadline-dev libxslt-dev libxml2-dev libicu-dev zlib1g-dev
RUN apt-get update
RUN apt-get install -yq libssl-dev
RUN apt-get install -yq git
RUN apt-get install -yq iputils-ping
RUN apt-get install -yq iproute2

RUN apt-get update
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install gettext
RUN docker-php-ext-install opcache
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install json
RUN docker-php-ext-install xmlrpc
RUN docker-php-ext-install xsl
RUN docker-php-ext-install intl
RUN docker-php-ext-install sockets
RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-install exif && docker-php-ext-enable exif
RUN docker-php-ext-install pcntl && docker-php-ext-enable pcntl
RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"


COPY config/ /
COPY entrypoint.sh /entrypoint.sh


ENTRYPOINT /bin/bash -c "/bin/bash /entrypoint.sh"
