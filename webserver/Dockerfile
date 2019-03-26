# GRIVA.GROUP default development environment
FROM nginx

# Author
MAINTAINER Vasily Grigoriev <v.grigoriev@griva.group>

# Version
ENV GG_DDE_MAINTAINER "Vasily Grigoriev <vasily.grigoriev@gridark.name>"
ENV GG_DDE_VERSION 0.3.0
ENV GG_DDE_LAST_UPDATE 2019.03.25

# Other environment variables
ENV TIMEZONE Europe/Moscow

# Include specify files and volumes
VOLUME ["/var/www", "/var/log/nginx/"]
COPY etc/www /etc/nginx/www
COPY etc/nginx.conf /etc/nginx/nginx.conf
COPY bin/startup.sh /usr/sbin/startup.sh

# Run init script
CMD ["/usr/sbin/startup.sh"]
