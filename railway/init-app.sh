#!/bin/bash
# Exécuté à chaque déploiement (Pre-Deploy Command sur Railway).
# chmod +x railway/init-app.sh

set -e

php artisan storage:link --force 2>/dev/null || true

php artisan migrate --force

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
