#!/usr/bin/env bash
## START SECTION -- LIVE FUNCTIONS
GGDDE_NGINX_CONF="/var/www/${VHOST_NAME}/env/php/etc/nginx"
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

# Create symlink for vhost folder and reset rights
cd /var/www && ln -sfnF vhost ${VHOST_NAME}
chown -R www-data:www-data /var/www

for poolfile in `find /usr/local/etc/php-fpm.d/ -type f`
do
     envsubst < $poolfile > `basename $poolfile`
done

# Add host loop to host machine
echo "$(/sbin/ip route|awk '/default/ { print $3 }')  ${VHOST_NAME}" >> /etc/hosts

# Running server
php-fpm

# For realy gracefull stoping container
wait $!
