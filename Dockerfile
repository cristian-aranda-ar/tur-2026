FROM wordpress:latest

# Fix Apache MPM conflict — remove conflicting modules at image build time
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_worker.conf \
          /etc/apache2/mods-enabled/mpm_worker.load && \
    ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf && \
    ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load

# Copy theme and plugins into the image
COPY wp-content /var/www/html/wp-content

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/wp-content

EXPOSE 80
