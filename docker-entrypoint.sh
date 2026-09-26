#!/bin/bash
set -e

# Support dynamic port binding on cloud platforms (Render, Railway, Fly.io)
PORT="${PORT:-80}"

# Adjust Apache listening port dynamically
if [ "$PORT" != "80" ]; then
    sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf 2>/dev/null || true
    sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/*.conf 2>/dev/null || true
fi

# Ensure only a single MPM (prefork) is enabled to prevent AH00534 error
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Ensure .env file exists so artisan commands (like key:generate) do not fail
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    else
        touch /var/www/html/.env
    fi
fi

# Ensure storage & bootstrap/cache directories exist with proper permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# If APP_KEY is empty, generate an application key safely
if [ -z "$APP_KEY" ]; then
    echo "Generating application encryption key..."
    php artisan key:generate --force || true
fi

# Run database migrations safely without crashing the container on connection errors
if [ "$AUTO_MIGRATE" = "true" ]; then
    if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "<your_db_host>" ] || [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ]; then
        echo "[Notice] DB_HOST is set to '${DB_HOST:-none}'. Please configure your real database host. Skipping migrations."
    else
        echo "Attempting database migrations on host '${DB_HOST}'..."
        php artisan migrate --force || echo "[Warning] Database migration could not complete. Continuing web server startup..."
    fi
fi

# Optimize views for production
php artisan view:cache || true

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
