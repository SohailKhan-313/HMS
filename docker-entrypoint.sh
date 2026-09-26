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

# Run our dedicated PHP script to initialize .env safely
php /var/www/html/docker-init-env.php || true

# If APP_KEY is still not present in .env, generate one
CURRENT_KEY=$(grep "^APP_KEY=" /var/www/html/.env 2>/dev/null | cut -d '=' -f2)
if [ -z "$CURRENT_KEY" ]; then
    echo "Generating application encryption key..."
    php artisan key:generate --force || true
fi

# Ensure SQLite file exists and database directory has write permissions
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Set directory permissions for web server
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache /var/www/html/database /var/www/html/.env
chmod -R 775 storage bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/.env /var/www/html/database/database.sqlite 2>/dev/null || true

# Run database migrations to ensure all tables (including sessions and hospital tables) exist
echo "Running database migrations..."
php artisan migrate --force || echo "[Warning] Database migration failed or skipped. Continuing startup..."

# Cache views for performance
php artisan view:cache || true

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
