#!/bin/bash
set -e
exec docker-entrypoint.sh apache2-foreground
