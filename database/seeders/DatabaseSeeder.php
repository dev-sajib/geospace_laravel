<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear existing data (optional - comment out if you want to keep existing data)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables in reverse dependency order
        $tables = [
            'notifications',
            'role_permissions',
            'menu_items',
            'activity_logs',
            'visitor_logs',
            'timesheet_day_comments',
            'timesheet_days',
            'timesheets',
            'timesheet_status',
            'payments',
            'payment_requests',
            'invoices',
            'freelancer_earnings',
            'dispute_messages',
            'dispute_tickets',
            'dispute_status',
            'contracts',
            'projects',
            'company_details',
            'user_details',
            'users',
            'roles',
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ✅ Seed tables in dependency order
        $this->call([
            // Step 1: Base tables without foreign keys
            RolesTableSeeder::class,

            // Step 2: Users depend on roles
            UsersTableSeeder::class,

            // Step 3: User-related tables
            UserDetailsTableSeeder::class,
            CompanyDetailsTableSeeder::class,

            // Step 4: Projects depend on company_details
            ProjectsTableSeeder::class,

            // Step 5: Contracts depend on projects and users
            ContractsTableSeeder::class,

            // Step 6: Status tables
            TimesheetStatusTableSeeder::class,
            DisputeStatusTableSeeder::class,

            // Step 7: Timesheets and related entities
            TimesheetsSeeder::class,
            TimesheetDaysSeeder::class,
            TimesheetDayCommentsSeeder::class,

            // Step 8: Financial flow
            InvoicesSeeder::class,
            PaymentRequestsSeeder::class,
            PaymentsSeeder::class,
            FreelancerEarningsSeeder::class,

            // Step 9: Menu and permissions
            MenuItemsTableSeeder::class,
            RolePermissionsTableSeeder::class,

            // Step 10: Notifications
            NotificationsTableSeeder::class,
        ]);

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $this->command->info('✅ All demo data (including timesheets & payments) seeded successfully!');
    }
}
