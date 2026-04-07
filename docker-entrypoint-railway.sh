#!/bin/bash
set -e

# Railway provides $PORT — update Apache to listen on it
PORT=${PORT:-80}

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-enabled/*.conf 2>/dev/null || true

# Run the original WordPress entrypoint
exec docker-entrypoint.sh apache2-foreground
