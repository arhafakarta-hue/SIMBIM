#!/bin/bash
# Railway build script

echo "🔨 Building SIMBIM for Railway..."

# Install dependencies
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Create necessary directories with absolute paths
echo "📁 Creating directories..."
mkdir -p /app/database
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/framework/cache
mkdir -p /app/storage/logs
mkdir -p /app/bootstrap/cache

# Create SQLite database file
echo "💾 Creating database file..."
touch /app/database/database.sqlite

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 /app/storage /app/bootstrap/cache /app/database

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true

echo "✅ Build complete!"
