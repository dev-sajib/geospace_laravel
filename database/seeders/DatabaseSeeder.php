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

        // Add other seeders here as needed, in order of dependencies
        // Example:
        // $this->call([
        //     UsersSeeder::class,
        //     ProjectsSeeder::class,
        //     ContractsSeeder::class,
        // ]);
    }
}
