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

# Copy host user to container for right fileaccess
ARG USER_ID
ARG GROUP_ID
RUN if [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ]; then \
    userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data &&\
    install -d -m 0755 -o www-data -g www-data /home/www-data \
;fi

# Устанавливаем сервис отправки электронных сообщений
ENV DEBIAN_FRONTEND noninteractive
RUN \
    echo Europe/Moscow | tee /etc/timezone && \
    dpkg-reconfigure --frontend noninteractive tzdata && \
    apt update && apt install -y postfix rsyslog sasl2-bin && \
    postconf -e "mydestination = localhost" && \
    postconf -e "smtpd_sasl_auth_enable=yes" && \
    postconf -e "broken_sasl_auth_clients=yes" && \
    postconf -e "smtpd_relay_restrictions=permit_sasl_authenticated,reject_unauth_destination" && \
    postconf -e "smtpd_sasl_security_options = noanonymous" && \
    echo "gwsGewg35tG#24452qew3RED" | saslpasswd2 -c -p -u example.com postfix && \
    ln  /etc/sasldb2 /var/spool/postfix/etc/sasldb2 && \
    mkfifo /var/spool/postfix/public/pickup && \
    adduser postfix sasl && \
    touch /var/log/mail.log
COPY etc/postfix/smtpd.conf /etc/postfix/sasl/smtpd.conf

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
