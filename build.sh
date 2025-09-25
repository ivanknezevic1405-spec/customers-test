#!/bin/bash

# Vercel build script for Laravel
echo "Starting Laravel build process for Vercel..."

# Install dependencies (production only)
composer install --optimize-autoloader --no-dev --prefer-dist --no-interaction

# Create storage directories
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache

# Generate application key
php artisan key:generate --force --no-interaction

# Don't cache config, routes, or views for Vercel - causes issues
# Just clear any existing caches
php artisan config:clear --quiet || true
php artisan route:clear --quiet || true  
php artisan view:clear --quiet || true
php artisan cache:clear --quiet || true

echo "Build completed successfully!"