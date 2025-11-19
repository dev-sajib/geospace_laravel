<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
            [
                'role_id' => 1,
                'role_name' => 'Admin',
                'role_description' => 'System Administrator with full access',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'role_name' => 'Freelancer',
                'role_description' => 'Freelancer user who provides services',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'role_name' => 'Company',
                'role_description' => 'Company user who posts projects',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'role_name' => 'Support',
                'role_description' => 'Support agent for customer service',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
