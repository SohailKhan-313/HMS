#!/bin/bash
set -e

# Support dynamic port binding on cloud platforms (Render, Railway, Fly.io)
PORT="${PORT:-80}"

# Cleanly write Apache ports configuration
cat <<EOF > /etc/apache2/ports.conf
Listen ${PORT}
EOF

# Cleanly write default VirtualHost configuration
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

# Prepare all writable directories and files
mkdir -p /var/www/html/storage/logs \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

touch /var/www/html/storage/logs/laravel.log
touch /var/www/html/database/database.sqlite

# Run our dedicated PHP script to initialize .env safely
php /var/www/html/docker-init-env.php || true

# If APP_KEY is still not present in .env, generate one
CURRENT_KEY=$(grep "^APP_KEY=" /var/www/html/.env 2>/dev/null | cut -d '=' -f2)
if [ -z "$CURRENT_KEY" ]; then
    echo "Generating application encryption key..."
    php artisan key:generate --force || true
fi

# Run database migrations with retry to allow MySQL service startup time
echo "Running database migrations..."
for attempt in 1 2 3 4 5 6; do
    if php artisan migrate --force; then
        echo "[Success] Database migrations completed."
        break
    fi
    echo "[Attempt $attempt/6] MySQL not ready yet or migrating failed. Retrying in 3 seconds..."
    sleep 3
done

# Cache views for performance
php artisan view:cache || true

# Re-apply complete permissions so Apache (www-data) can read/write everything without permission errors
chown -R www-data:www-data /var/www/html/storage \
                           /var/www/html/bootstrap/cache \
                           /var/www/html/database \
                           /var/www/html/public \
                           /var/www/html/.env
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/database /var/www/html/public
chmod 664 /var/www/html/.env /var/www/html/database/database.sqlite 2>/dev/null || true
chmod 666 /var/www/html/storage/logs/laravel.log 2>/dev/null || true

echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
