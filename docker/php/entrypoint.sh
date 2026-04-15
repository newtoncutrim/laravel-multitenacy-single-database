#!/bin/sh
set -e

cd /var/www

mkdir -p storage/framework

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if [ ! -f vendor/autoload.php ]; then
    lock_dir="storage/framework/.composer-install.lock"

    if mkdir "$lock_dir" 2>/dev/null; then
        trap 'rmdir "$lock_dir" 2>/dev/null || true' EXIT INT TERM
        composer install --no-interaction --prefer-dist --optimize-autoloader
        rmdir "$lock_dir" 2>/dev/null || true
        trap - EXIT INT TERM
    else
        echo "Waiting for Composer dependencies to be installed..."
        while [ ! -f vendor/autoload.php ] && [ -d "$lock_dir" ]; do
            sleep 2
        done

        if [ ! -f vendor/autoload.php ]; then
            composer install --no-interaction --prefer-dist --optimize-autoloader
        fi
    fi
fi

if [ -f artisan ] && ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --ansi
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chmod -R ug+rw storage bootstrap/cache

exec "$@"
