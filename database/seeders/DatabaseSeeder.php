<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Seed lookup/reference tables first (no foreign key dependencies)
        $this->call([
            RolesTableSeeder::class,
            TimesheetStatusSeeder::class,
            DisputeStatusSeeder::class,
        ]);

        // Seed users (depends on roles)
        $this->call([
            UsersTableSeeder::class,
        ]);
    }
}
