#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY not set, generating one..."
    php artisan key:generate --force --no-interaction
else
    echo "✅ APP_KEY is set"
fi

# Run database migrations
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database if needed (optional)
if [ "$SEED_DATABASE" = "true" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force --no-interaction
fi

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the application server
echo "🎉 Starting PHP server on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT