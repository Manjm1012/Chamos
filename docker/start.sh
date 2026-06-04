#!/bin/sh
set -e

cd /var/www/html

# Generate app key if missing
php artisan key:generate --no-interaction --force

# Run migrations
php artisan migrate --force --no-interaction

# Seed only if tables are empty
php artisan db:seed --force --no-interaction 2>/dev/null || true

# Publish vendor assets
php artisan livewire:publish --assets --quiet

# Clear and cache config
php artisan optimize:clear
php artisan config:cache
php artisan view:cache

# Fix storage permissions
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Chamos started successfully"
exec /usr/bin/supervisord -c /etc/supervisord.conf
