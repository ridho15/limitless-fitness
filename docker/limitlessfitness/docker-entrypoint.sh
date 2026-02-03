#!/bin/bash
set -e

# If .env missing, copy .env.example
if [ ! -f /var/www/html/.env ] && [ -f /var/www/html/.env.example ]; then
  echo "[entrypoint] Copying .env.example to .env"
  cp /var/www/html/.env.example /var/www/html/.env
fi

# Wait for DB if configured
if [ -n "$DB_HOST" ]; then
  echo "[entrypoint] Waiting for DB at $DB_HOST:$DB_PORT (timeout: ${DB_WAIT_TIMEOUT:-60}s)"
  /usr/local/bin/wait-for-db.sh
fi

# Ensure Laravel writable dirs exist and permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# If vendor not present, run composer install
if [ ! -d "/var/www/html/vendor" ]; then
  echo "[entrypoint] Running composer install (if composer.json present)..."
  if [ -f "/var/www/html/composer.json" ]; then
    cd /var/www/html
    composer install --no-interaction --no-ansi --optimize-autoloader --no-scripts || true
  fi
fi

# Optionally run migrations if requested
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "[entrypoint] Running database migrations..."
  php artisan migrate --force || true
fi

# Ensure PHP-FPM listens on TCP 127.0.0.1:9000 for nginx
if [ -f /usr/local/etc/php-fpm.d/www.conf ]; then
  sed -i 's|^listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf || true
fi

# Start PHP-FPM in background
echo "[entrypoint] Starting php-fpm"
php-fpm -D || true

# Finally exec the main process (nginx foreground by default)
exec "$@"
