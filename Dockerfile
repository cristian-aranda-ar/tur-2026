FROM wordpress:latest

# Copy theme and plugins into the image
COPY wp-content /var/www/html/wp-content

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/wp-content

# Railway injects $PORT — configure Apache to listen on it
COPY docker-entrypoint-railway.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint-railway.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint-railway.sh"]
