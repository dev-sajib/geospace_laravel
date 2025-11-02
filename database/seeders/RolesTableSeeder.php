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
                'role_description' => 'System administrator with full access',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
            [
                'role_id' => 2,
                'role_name' => 'Freelancer',
                'role_description' => 'Freelance geologists and professionals',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
            [
                'role_id' => 3,
                'role_name' => 'Company',
                'role_description' => 'Companies hiring freelancers',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
            [
                'role_id' => 4,
                'role_name' => 'Support',
                'role_description' => 'Support agents handling disputes and chat',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
            [
                'role_id' => 5,
                'role_name' => 'Visitor',
                'role_description' => 'Guest users browsing the platform',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
        ]);
    }
}
