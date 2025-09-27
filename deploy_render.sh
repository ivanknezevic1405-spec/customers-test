#!/bin/bash

echo "🎨 Running Render deployment hooks..."

# Run database migrations
php artisan migrate --force --no-interaction

# Seed database (optional - remove if you don't want sample data)
php artisan db:seed --force --no-interaction

# Clear and cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment hooks completed!"