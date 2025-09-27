#!/bin/bash

# Railway deployment script
echo "🚂 Starting Railway deployment..."

# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate app key if needed
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Seed the database (optional)
php artisan db:seed --force

# Cache configuration for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Railway deployment completed!"