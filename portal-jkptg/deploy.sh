#!/usr/bin/env bash
# Portal JKPTG — Hostinger deployment script
# Run on the Hostinger server after `git pull origin master`.
#
# Usage:
#   ssh user@server "cd /home/u_jkptg/public_html && git pull && ./deploy.sh"
#
# Or wire as Hostinger Git auto-deploy hook.

set -euo pipefail

cd "$(dirname "$0")"

echo "==> Composer install (no-dev, optimized)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "==> Migrate database (forced)"
php artisan migrate --force

echo "==> Storage symlink"
php artisan storage:link 2>/dev/null || true

echo "==> Cache config / routes / views / events"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> Re-import Scout indexes"
php artisan scout:import "App\\Models\\Page"
php artisan scout:import "App\\Models\\Service"
php artisan scout:import "App\\Models\\News"
php artisan scout:import "App\\Models\\Tender"
php artisan scout:import "App\\Models\\Form"
php artisan scout:import "App\\Models\\Faq"
php artisan scout:import "App\\Models\\ChatbotKnowledge"

echo "==> Filament/Livewire cleanup"
php artisan filament:optimize 2>/dev/null || true
php artisan livewire:discover 2>/dev/null || true

echo "==> Restart PHP-FPM (Hostinger handles this; uncomment if needed)"
# sudo systemctl reload php8.2-fpm

echo "==> Permissions"
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "==> Done."
