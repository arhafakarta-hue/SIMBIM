#!/bin/bash
# Railway build script

echo "🔨 Building SIMBIM for Railway..."

# Install dependencies
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p database
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Create SQLite database file
echo "💾 Creating database file..."
touch database/database.sqlite

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache database

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "✅ Build complete!"
