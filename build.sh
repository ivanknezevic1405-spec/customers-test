#!/bin/bash
set -e

echo "🚀 Starting Laravel build for Vercel..."

# Install dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev --prefer-dist --no-interaction

# Create required directories
echo "📁 Creating directories..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions  
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage bootstrap/cache

echo "✅ Build completed successfully!"