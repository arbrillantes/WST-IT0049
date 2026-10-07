#!/bin/sh
set -eu

PORT="${PORT:-10000}"
case "$PORT" in
    ''|*[!0-9]*) echo 'PORT must be a number.' >&2; exit 1 ;;
esac
if [ "$PORT" -lt 1 ] || [ "$PORT" -gt 65535 ]; then
    echo 'PORT must be between 1 and 65535.' >&2
    exit 1
fi
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# CodeIgniter accepts underscore-separated environment configuration keys.
if [ -z "${app_baseURL:-}" ] && [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
    export app_baseURL="${RENDER_EXTERNAL_URL%/}/"
fi

# Enable after configuring the hosted database in Render's environment settings.
if [ "${RUN_MIGRATIONS:-false}" = 'true' ]; then
    php spark migrate --all
fi
if [ "${SEED_ADMIN:-false}" = 'true' ]; then
    php spark db:seed AdminSeeder
fi

exec apache2-foreground
