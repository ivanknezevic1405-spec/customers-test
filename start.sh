#!/bin/bash
set -e

echo "🚀 Starting Laravel application..."

# Ensure APP_URL is set for production
if [ "$APP_ENV" = "production" ] && [ -z "$APP_URL" ]; then
    echo "⚠️  Setting default APP_URL for production..."
    export APP_URL="https://customers-test.onrender.com"
fi

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY not set, generating one..."
    php artisan key:generate --force --no-interaction
else
    echo "✅ APP_KEY is set"
fi

# Clear application caches (non-database dependent)
echo "🧹 Clearing application caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Publish and ensure Filament assets are available
echo "📦 Publishing Filament assets..."
php artisan filament:assets

# Check database connection
echo "🔍 Checking database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful!';"

# Run database migrations (this creates tables if they don't exist)
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

# Show migration status for debugging
echo "📋 Migration status:"
php artisan migrate:status

# Now we can safely clear database cache
echo "🧹 Clearing database cache..."
php artisan cache:clear

# Seed database if needed (optional)
if [ "$SEED_DATABASE" = "true" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force --no-interaction
fi

# Create admin user if environment variables are provided
if [ -n "$ADMIN_EMAIL" ] && [ -n "$ADMIN_PASSWORD" ]; then
    echo "👤 Creating admin user..."
    php artisan admin:create --name="$ADMIN_NAME" --email="$ADMIN_EMAIL" --password="$ADMIN_PASSWORD" || echo "⚠️  Admin user might already exist"
fi

# Clear any existing caches and optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Start the application server
echo "🎉 Starting PHP server on port $PORT..."
exec php artisan serve --host=0.0.0.0 --port=$PORT