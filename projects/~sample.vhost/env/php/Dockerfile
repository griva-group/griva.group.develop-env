FROM debian:stretch-slim

RUN apt-get update && apt-get install -y --no-install-recommends \
    wget \
    gettext-base \
    git \
    apt-transport-https \
    ca-certificates \
    iproute2

ARG PHP_VERSION=7.3
ENV PHP_VERSION=${PHP_VERSION}

# Добавляем необходимые репозитории и устанавливаем пакеты веб-сервера
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg  && \
    echo "deb https://packages.sury.org/php/ $(awk -F"[)(]+" '/VERSION=/ {print $2}' /etc/os-release) main" > \
        /etc/apt/sources.list.d/php.list && \
    apt-get update && \
    apt-get install -y --no-install-recommends \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-json \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-pdo-mysql \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-zip

# Устанавливаем composer
RUN php -r "copy('https://getcomposer.org/installer', '/composer-setup.php');" && \
    php /composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm /composer-setup.php

# Добавляем файлы конфигурации nginx и php в контейнер
VOLUME ["/var/log/php-fpm-alternative", "/etc/php/all/fpm"]
COPY bin/startup.sh /usr/sbin/startup.sh
RUN chmod +x /usr/sbin/startup.sh

# Данная точка входа служит для правильного запуска контейнера
CMD ["/usr/sbin/startup.sh"]
