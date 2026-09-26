#!/bin/bash
set -e

# Support dynamic port binding on cloud platforms (Render, Railway, Fly.io)
PORT="${PORT:-80}"

# Cleanly set Apache to listen ONLY on the assigned PORT (prevents duplicate port binding errors)
echo "Listen ${PORT}" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

# Ensure only a single MPM (prefork) is enabled to prevent AH00534 error
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Ensure .env file exists
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    else
        touch /var/www/html/.env
    fi
fi

# Write APP_KEY to .env if provided in environment, or generate one
if [ -n "$APP_KEY" ]; then
    if grep -q "^APP_KEY=" /var/www/html/.env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" /var/www/html/.env
    else
        echo "APP_KEY=${APP_KEY}" >> /var/www/html/.env
    fi
else
    CURRENT_KEY=$(grep "^APP_KEY=" /var/www/html/.env 2>/dev/null | cut -d '=' -f2)
    if [ -z "$CURRENT_KEY" ]; then
        echo "Generating application encryption key..."
        php artisan key:generate --force || true
    fi
fi

# Sync database environment variables to .env so Laravel Dotenv always reads them
if [ -n "$DB_HOST" ]; then
    if grep -q "^DB_HOST=" /var/www/html/.env; then
        sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" /var/www/html/.env
    else
        echo "DB_HOST=${DB_HOST}" >> /var/www/html/.env
    fi
fi
if [ -n "$DB_DATABASE" ]; then
    if grep -q "^DB_DATABASE=" /var/www/html/.env; then
        sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" /var/www/html/.env
    else
        echo "DB_DATABASE=${DB_DATABASE}" >> /var/www/html/.env
    fi
fi
if [ -n "$DB_USERNAME" ]; then
    if grep -q "^DB_USERNAME=" /var/www/html/.env; then
        sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" /var/www/html/.env
    else
        echo "DB_USERNAME=${DB_USERNAME}" >> /var/www/html/.env
    fi
fi
if [ -n "$DB_PASSWORD" ]; then
    if grep -q "^DB_PASSWORD=" /var/www/html/.env; then
        sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" /var/www/html/.env
    else
        echo "DB_PASSWORD=${DB_PASSWORD}" >> /var/www/html/.env
    fi
fi
if [ -n "$DB_PORT" ]; then
    if grep -q "^DB_PORT=" /var/www/html/.env; then
        sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|" /var/www/html/.env
    else
        echo "DB_PORT=${DB_PORT}" >> /var/www/html/.env
    fi
fi

# Ensure storage & bootstrap/cache directories and .env have proper permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache /var/www/html/.env
chmod -R 775 storage bootstrap/cache
chmod 664 /var/www/html/.env

# Run database migrations safely without crashing the container on connection errors
if [ "$AUTO_MIGRATE" = "true" ]; then
    if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "<your_db_host>" ] || [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ]; then
        echo "[Notice] DB_HOST is set to '${DB_HOST:-none}'. Please configure your real database host. Skipping migrations."
    else
        echo "Attempting database migrations on host '${DB_HOST}'..."
        php artisan migrate --force || echo "[Warning] Database migration could not complete. Continuing web server startup..."
    fi
fi

# Optimize views
php artisan view:cache || true

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
