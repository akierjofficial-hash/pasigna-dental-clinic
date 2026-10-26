#!/usr/bin/env bash
set -e

echo "Running Pasigna Laravel startup tasks"

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache || true

php artisan optimize:clear || true

php artisan migrate --force

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

php artisan storage:link || true
