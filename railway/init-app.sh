#!/bin/sh
set -eu

php artisan migrate --force
php artisan optimize:clear

if [ -n "${ADMIN_EMAIL:-}" ] && [ -n "${ADMIN_PASSWORD:-}" ]; then
    php artisan db:seed --class=ProductionAdminSeeder --force
fi

if [ "${BOOTSTRAP_DATA:-false}" = "true" ]; then
    php artisan fetch:all
fi

php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
