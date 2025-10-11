#!/bin/bash

# GeoSpace Database Setup Script
# This script will create the database, run migrations, and seed data

echo "🚀 Starting GeoSpace Database Setup..."

# Check if MySQL is running
if ! pgrep -x "mysqld" > /dev/null; then
    echo "❌ MySQL is not running. Please start MySQL first."
    echo "   You can start MySQL with: brew services start mysql"
    exit 1
fi

# Create the database if it doesn't exist
echo "📊 Creating database 'geospace_db'..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS geospace_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if [ $? -eq 0 ]; then
    echo "✅ Database 'geospace_db' created successfully"
else
    echo "❌ Failed to create database"
    exit 1
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

if [ $? -eq 0 ]; then
    echo "✅ PHP dependencies installed successfully"
else
    echo "❌ Failed to install PHP dependencies"
    exit 1
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo "✅ Migrations completed successfully"
else
    echo "❌ Migrations failed"
    exit 1
fi

# Run seeders
echo "🌱 Seeding database with sample data..."
php artisan db:seed --force

if [ $? -eq 0 ]; then
    echo "✅ Database seeded successfully"
else
    echo "❌ Database seeding failed"
    exit 1
fi

# Clear caches
echo "🧹 Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🎉 Database setup completed successfully!"
echo ""
echo "📋 Summary:"
echo "   - Database: geospace_db"
echo "   - Users: 19 (1 Admin, 8 Freelancers, 8 Companies, 2 Support)"
echo "   - Projects: 10 active projects"
echo "   - Contracts: 10 active contracts"
echo "   - Timesheets: 13 timesheets (10 approved, 3 pending)"
echo "   - Invoices: 13 invoices (10 paid, 3 pending)"
echo "   - Notifications: 21 notifications"
echo "   - Skills: 36 geological and technical skills"
echo ""
echo "🔐 Default login credentials:"
echo "   Admin: admin@geospace.com / password"
echo "   Freelancer: john.smith@gmail.com / password"
echo "   Company: contact@northernmining.com / password"
echo ""
echo "🚀 You can now start the Laravel server with: php artisan serve"
