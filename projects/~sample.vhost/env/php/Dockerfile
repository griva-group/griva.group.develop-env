FROM debian:stretch-slim

# Author
MAINTAINER Vasily Grigoriev <vasily.grigoriev@gridark.name>

# Version
ENV GG_DDE_COMPONENT "PHP-FPM"
ENV GG_DDE_MAINTAINER "Vasily Grigoriev <vasily.grigoriev@gridark.name>"
ENV GG_DDE_VERSION 0.3.0
ENV GG_DDE_LAST_UPDATE 2019.03.25

# Update repositories list and install required packages
RUN set -x \
    && apt-get update \
    && apt-get install -y --no-install-recommends wget apt-transport-https ca-certificates git gettext-base

# Other environment variables
ARG PHP_VERSION=7.3
ENV PHP_VERSION=${PHP_VERSION}
ENV TIMEZONE Europe/Moscow

# Добавляем необходимые репозитории и устанавливаем пакеты веб-сервера
RUN set -x \
    && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
    && echo "deb https://packages.sury.org/php/ stretch main" > /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-gd \
#        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-json \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-pdo-mysql \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-zip

# Устанавливаем composer
RUN set -x \
    && php -r "copy('https://getcomposer.org/installer', '/composer-setup.php');" \
    && php /composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm /composer-setup.php

## Добавляем файлы конфигурации nginx и php в контейнер
VOLUME ["/var/log/php-fpm-alternative", "/etc/php/all/fpm"]
COPY bin/startup.sh /usr/sbin/startup
RUN chmod +x /usr/sbin/startup

# Данная точка входа служит для правильного запуска контейнера
CMD ["/usr/sbin/startup"]
