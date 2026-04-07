FROM wordpress:latest

# Fix Apache MPM conflict
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true && \
    a2enmod mpm_prefork

# Copy theme and plugins into the image
COPY wp-content /var/www/html/wp-content

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/wp-content

EXPOSE 80
