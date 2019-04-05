#!/usr/bin/env bash

## START SECTION -- LIVE FUNCTIONS
GGDDE_NGINX_CONF="/var/www/$(ls /var/www | head -1)/env/php/etc"

_activate_nginx_host() {
    if [ -f "$GGDDE_NGINX_CONF/nginx.conf" ]; then
        envsubst "`printf '${%s} ' $(bash -c "compgen -A variable")`" < "$GGDDE_NGINX_CONF/nginx.conf" > "$GGDDE_NGINX_CONF/live.nginx.conf"
    else
        echo "NOTICE: file $GGDDE_NGINX_CONF/nginx.conf not found!"
    fi
}
_disable_nginx_host() {
    if [ -f "$GGDDE_NGINX_CONF/live.nginx.conf" ]; then
        rm "$GGDDE_NGINX_CONF/live.nginx.conf"
    fi
}

_activate_nginx_host
trap _disable_nginx_host INT TERM
## END SECTION -- LIVE FUNCTIONS

# Create symlink for vhost folder
ln -sfnF /var/www/vhost /var/www/${PHP_DIR}
chown -R www-data:www-data /var/www/${PHP_DIR}

# Change php-fpm config dir
cd /etc/php/${PHP_VERSION}/fpm/

# Copy php-fpm configs
rm php.ini      && ln -s ../../all/fpm/php.ini      php.ini
rm php-fpm.conf && ln -s ../../all/fpm/php-fpm.conf php-fpm.conf

# Generate php-fpm pools config
cd pool.d/ && rm -r ./*
for poolfile in `find /etc/php/all/fpm/pool.d/ -type f`
do
    envsubst "`printf '${%s} ' $(bash -c "compgen -A variable")`" < $poolfile > `basename $poolfile`
done

# Add host loop to host machine
echo "$(/sbin/ip route|awk '/default/ { print $3 }')  ${PHP_HOST}" >> /etc/hosts

# Running server
/usr/sbin/php-fpm${PHP_VERSION} --nodaemonize &

# For realy gracefull stoping container
wait $!
