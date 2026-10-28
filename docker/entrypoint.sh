#!/bin/sh
set -e

echo "=== Website Uptime Monitor Entrypoint ==="

# Wait for database if needed
if [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Waiting for PostgreSQL ($DB_HOST:$DB_PORT)..."
    while ! nc -z "$DB_HOST" "$DB_PORT"; do
        sleep 1
    done
    echo "PostgreSQL is ready."
fi

# Run database migrations
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Sync 60 modules
php artisan uptime:install --only-sync-modules || true

# Cache config and routes in production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing application for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Ensure storage directories permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

exec "$@"
