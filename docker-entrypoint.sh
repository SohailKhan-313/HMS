#!/bin/bash
set -e

# Support dynamic port binding on cloud platforms like Render, Railway, Fly.io
PORT="${PORT:-80}"

# Adjust Apache listening port dynamically
if [ "$PORT" != "80" ]; then
    sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf 2>/dev/null || true
    sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/*.conf 2>/dev/null || true
fi

# Ensure storage directories exist with proper permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# If APP_KEY is empty, generate an application key
if [ -z "$APP_KEY" ]; then
    echo "Generating application encryption key..."
    php artisan key:generate --force || true
fi

# Run database migrations if AUTO_MIGRATE=true
if [ "$AUTO_MIGRATE" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

# Optimize views for production
php artisan view:cache || true

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
