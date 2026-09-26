#!/bin/bash
set -e

# Support dynamic port binding on cloud platforms (Render, Railway, Fly.io)
PORT="${PORT:-80}"

# Cleanly write Apache ports configuration (no sed, no duplicate ports)
cat <<EOF > /etc/apache2/ports.conf
Listen ${PORT}
EOF

# Cleanly write default VirtualHost configuration (no sed, no syntax errors)
cat <<EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:${PORT}>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Ensure only prefork MPM is enabled
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Run our dedicated PHP script to initialize .env safely without sed
php /var/www/html/docker-init-env.php || true

# If APP_KEY is still not present in .env, generate one
CURRENT_KEY=$(grep "^APP_KEY=" /var/www/html/.env 2>/dev/null | cut -d '=' -f2)
if [ -z "$CURRENT_KEY" ]; then
    echo "Generating application encryption key..."
    php artisan key:generate --force || true
fi

# Set directory permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache /var/www/html/.env
chmod -R 775 storage bootstrap/cache
chmod 664 /var/www/html/.env

# Run database migrations safely
if [ "$AUTO_MIGRATE" = "true" ]; then
    if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "<your_db_host>" ] || [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ]; then
        echo "[Notice] DB_HOST is set to '${DB_HOST:-none}'. Skipping automatic migrations."
    else
        echo "Attempting database migrations on host '${DB_HOST}'..."
        php artisan migrate --force || echo "[Warning] Database migration failed. Continuing web server startup..."
    fi
fi

# Cache views for performance
php artisan view:cache || true

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
