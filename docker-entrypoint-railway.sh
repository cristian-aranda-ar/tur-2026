#!/bin/bash
set -e

PORT=${PORT:-80}

# Only patch ports if Railway assigned a non-standard port
if [ "$PORT" != "80" ]; then
  sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
  sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-enabled/000-default.conf
fi

exec docker-entrypoint.sh apache2-foreground
