#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

cd "$ROOT_DIR"

echo "==> Installing PHP dependencies"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "==> Installing frontend dependencies"
npm ci

echo "==> Building production assets"
npm run build

echo "==> Running database migrations"
php artisan migrate --force

echo "==> Clearing and rebuilding caches"
php artisan optimize:clear
php artisan config:cache
php artisan view:cache

echo "Deployment completed successfully."