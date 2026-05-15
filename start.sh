#!/bin/bash
# Railway start script - ensures database exists before starting

echo "🚀 Starting SIMBIM..."

# Ensure database directory and file exist
mkdir -p /app/database
touch /app/database/database.sqlite
chmod 775 /app/database/database.sqlite

echo "✅ Database file ready at /app/database/database.sqlite"

# Start the application
exec php artisan serve --host=0.0.0.0 --port=$PORT
