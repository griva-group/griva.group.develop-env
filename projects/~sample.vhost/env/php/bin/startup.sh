#!/usr/bin/env bash
## START SECTION -- LIVE FUNCTIONS
GGDDE_NGINX_CONF="/var/www/vhost/env/php/etc/nginx"
_activate_nginx_host() {
    if [ -f "$GGDDE_NGINX_CONF/nginx.conf" ]; then
        envsubst < "$GGDDE_NGINX_CONF/nginx.conf" > "$GGDDE_NGINX_CONF/live.nginx.conf"
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

echo ${GGDDE_NGINX_CONF}

# Add host loop to host machine
#echo "$(/sbin/ip route|awk '/default/ { print $3 }')  ${PHP_HOST}" >> /etc/hosts

# Create symlink for vhost folder and reset rights
cd /var/www && ln -sfnF vhost ${PHP_DIR}
chown -R www-data:www-data /var/www

for poolfile in `find /usr/local/etc/php-fpm.d/ -type f`
do
     envsubst < $poolfile > `basename $poolfile`
done

# Running server
php-fpm

# For realy gracefull stoping container
wait $!
