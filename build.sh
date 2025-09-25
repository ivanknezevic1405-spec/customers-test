#!/bin/bash

# Vercel build script
echo "Starting Laravel build process..."

# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key if not set
php artisan key:generate --force --no-interaction

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force --no-interaction

echo "Build completed successfully!"