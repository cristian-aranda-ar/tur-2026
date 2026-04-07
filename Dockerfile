FROM wordpress:latest

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Copy theme and plugins into the image
COPY wp-content /var/www/html/wp-content

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/wp-content

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
