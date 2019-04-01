# GRIVA.GROUP default development environment
FROM nginx

# Other environment variables
ENV TIMEZONE Europe/Moscow

# Include specify files and volumes
VOLUME ["/var/www", "/var/log/nginx/"]
COPY etc/www /etc/nginx/www
COPY etc/nginx.conf /etc/nginx/nginx.conf
COPY bin/startup.sh /usr/sbin/startup.sh
RUN chmod +x /usr/sbin/startup.sh

# Run init script
CMD ["/usr/sbin/startup.sh"]
