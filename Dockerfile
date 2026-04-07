FROM wordpress:latest

# Copy theme and plugins into the image
COPY wp-content /var/www/html/wp-content

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/wp-content

EXPOSE 80
