# GeoSpace Database Migration & Seeder Guide

This document provides instructions for recreating the GeoSpace database using Laravel migrations and seeders.

## Overview

All database migrations and seeders have been automatically generated from your existing database schema. This allows you to:
- Recreate the database structure on any environment
- Version control your database schema
- Roll back changes when needed
- Seed reference data automatically

## What Was Generated

### Migrations (62 files total)
Located in: `database/migrations/`

**Table Creation Migrations** (35 files):
- All tables from your database (users, projects, contracts, invoices, etc.)
- Each table has its own migration file
- Includes all columns, indexes, and constraints

**Foreign Key Migrations** (27 files):
- Separate migrations for adding foreign key constraints
- Runs after table creation to avoid dependency issues

### Seeders (4 files)
Located in: `database/seeders/`

1. **RolesTableSeeder.php** - Seeds 5 roles (Admin, Freelancer, Company, Support, Visitor)
2. **TimesheetStatusSeeder.php** - Seeds 7 timesheet statuses
3. **DisputeStatusSeeder.php** - Seeds 4 dispute statuses
4. **DatabaseSeeder.php** - Master seeder that calls all seeders in order

## Database Tables Included

```
activity_logs               menu_items                 timesheet_days
certifications             messages                   timesheet_status
company_details            notifications              timesheets
contracts                  payment_requests           user_details
conversation_participants  payments                   users
conversations              portfolio                  video_support_requests
dispute_messages           projects                   visitor_logs
dispute_status             role_permissions           work_experience
dispute_tickets            roles
education                  skills
expertise                  timesheet_day_comments
feedback
file_uploads
freelancer_earnings
invoices
jobs
```

## How to Use

### Step 1: Fresh Installation

If you want to recreate the database from scratch:

```bash
# Drop all tables and re-run all migrations
php artisan migrate:fresh

# Run migrations and seed data
php artisan migrate:fresh --seed
```

### Step 2: Run Migrations Only

To run migrations without dropping existing tables:

```bash
# Run all pending migrations
php artisan migrate

# Check migration status
php artisan migrate:status
```

### Step 3: Run Seeders Only

To seed data without running migrations:

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=RolesTableSeeder
php artisan db:seed --class=TimesheetStatusSeeder
php artisan db:seed --class=DisputeStatusSeeder
```

### Step 4: Rollback Migrations

To undo migrations:

```bash
# Rollback last batch
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Rollback and re-run all migrations
php artisan migrate:refresh

# Rollback, re-run, and seed
php artisan migrate:refresh --seed
```

## Migration Files Structure

### Table Creation Example (users table)

```php
Schema::create('users', function (Blueprint $table) {
    $table->integer('user_id', true);
    $table->string('email')->unique('email');
    $table->string('password_hash');
    $table->integer('role_id')->index('idx_users_role_id');
    // ... more columns
    $table->timestamp('created_at')->nullable()->useCurrent();
    $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
});
```

### Foreign Key Addition Example

```php
Schema::table('users', function (Blueprint $table) {
    $table->foreign(['role_id'], 'users_role_id_foreign')
          ->references(['role_id'])
          ->on('roles')
          ->onUpdate('no action')
          ->onDelete('cascade');
});
```

## Seeder Execution Order

The `DatabaseSeeder` runs seeders in dependency order:

1. **RolesTableSeeder** - Must run first (no dependencies)
2. **TimesheetStatusSeeder** - Must run first (no dependencies)
3. **DisputeStatusSeeder** - Must run first (no dependencies)

To add more seeders, edit `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        RolesTableSeeder::class,
        TimesheetStatusSeeder::class,
        DisputeStatusSeeder::class,
        // Add your custom seeders here
        // UsersSeeder::class,
        // ProjectsSeeder::class,
    ]);
}
```

## Adding Custom Seeders

### Create a new seeder:

```bash
php artisan make:seeder UsersSeeder
```

### Edit the seeder file:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'admin@geospace.com',
            'password_hash' => bcrypt('password'),
            'role_id' => 1,
            // ... more fields
        ]);
    }
}
```

### Add to DatabaseSeeder:

```php
$this->call([
    RolesTableSeeder::class,
    TimesheetStatusSeeder::class,
    DisputeStatusSeeder::class,
    UsersSeeder::class, // Add your seeder
]);
```

## Environment Configuration

Make sure your `.env` file has correct database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=geospace_db
DB_USERNAME=root
DB_PASSWORD=
```

## Testing the Setup

### Full Reset and Recreation:

```bash
# 1. Drop all tables and recreate with seeders
php artisan migrate:fresh --seed

# 2. Verify migrations ran successfully
php artisan migrate:status

# 3. Check seeded data
mysql -u root geospace_db -e "SELECT * FROM roles;"
mysql -u root geospace_db -e "SELECT * FROM timesheet_status;"
mysql -u root geospace_db -e "SELECT * FROM dispute_status;"
```

## Important Notes

1. **Backup First**: Always backup your database before running migrations
2. **Foreign Keys**: Foreign key migrations run after table creation
3. **Order Matters**: Migrations run in timestamp order
4. **Data Loss**: `migrate:fresh` will drop all tables and data
5. **Production**: Use `migrate` in production, not `migrate:fresh`

## Troubleshooting

### Issue: Foreign key constraint fails

**Solution**: Make sure parent tables are created before child tables. The generated migrations handle this automatically.

### Issue: Duplicate entry error when seeding

**Solution**: Clear the table before seeding:

```php
DB::table('roles')->truncate(); // Add to seeder before insert
```

### Issue: Migration already exists

**Solution**: Check migration status and run only pending:

```bash
php artisan migrate:status
php artisan migrate
```

## Generated By

- **Tool**: kitloong/laravel-migrations-generator v7.2.0
- **Date**: October 28, 2025
- **Database**: geospace_db
- **Total Tables**: 35
- **Total Migrations**: 62 (35 table + 27 foreign key)
- **Total Seeders**: 4 (3 data + 1 master)

## Additional Resources

- [Laravel Migrations Documentation](https://laravel.com/docs/migrations)
- [Laravel Seeding Documentation](https://laravel.com/docs/seeding)
- [Migration Generator Package](https://github.com/kitloong/laravel-migrations-generator)

---

**Note**: This setup recreates your database structure. If you need to migrate actual data from the existing database, you'll need to create additional seeders or use database exports/imports.
