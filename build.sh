#!/bin/bash

# Vercel build script for Laravel
echo "Starting Laravel build process for Vercel..."

# Install dependencies
composer install --optimize-autoloader --no-dev --prefer-dist

# Generate application key if not set
php artisan key:generate --force --no-interaction

# Create storage directories
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set proper permissions
chmod -R 775 storage bootstrap/cache

# Clear and cache optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Build completed successfully!"