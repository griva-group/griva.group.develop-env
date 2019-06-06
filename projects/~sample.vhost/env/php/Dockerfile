ARG PHP_VERSION=7.3
FROM php:${PHP_VERSION}-fpm

# Install special strings functions
RUN curl -L https://github.com/a8m/envsubst/releases/download/v1.1.0/envsubst-`uname -s`-`uname -m` -o envsubst && \
    chmod +x envsubst && \
    mv envsubst /usr/local/bin

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', '/composer-setup.php');" && \
    php /composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm /composer-setup.php

# Install helper script for php-extensions instalation
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

# Install php extintions
RUN install-php-extensions \
    curl \
    gd \
    json \
    opcache \
    mbstring \
    mysqli \
    pdo_mysql \
    xml \
    zip


# Добавляем файлы конфигурации nginx и php в контейнер
COPY etc/php/php.ini /usr/local/etc/php/php.ini
COPY etc/php/pool.d /usr/local/etc/php-fpm.d
COPY etc/php/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY bin/startup.sh /usr/sbin/startup.sh
RUN chmod +x /usr/sbin/startup.sh

# Edit default user
WORKDIR /var/www/vhost

# Данная точка входа служит для правильного запуска контейнера
CMD ["/usr/sbin/startup.sh"]
