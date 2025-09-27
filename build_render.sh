#!/bin/bash
set -e

echo "🎨 Starting Render build process..."

# Update package manager and install dependencies
composer install --optimize-autoloader --no-dev --no-interaction

# Create storage directories  
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 775 storage bootstrap/cache

# Generate app key
php artisan key:generate --force --no-interaction

echo "✅ Build completed successfully!"