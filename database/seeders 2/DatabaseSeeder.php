<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserDetailSeeder::class,
            CompanyDetailSeeder::class,
            SkillsSeeder::class,
            UserSkillsSeeder::class,
            ProjectsSeeder::class,
            ContractsSeeder::class,
            TimesheetStatusSeeder::class,
            TimesheetsSeeder::class,
            InvoicesSeeder::class,
            PaymentsSeeder::class,
            NotificationsSeeder::class,
            DisputeStatusSeeder::class,
            DropdownSeeder::class,
            MenuItemSeeder::class,
            RoleMenuAccessSeeder::class,
        ]);
    }
}
