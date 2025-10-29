<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Default password for all demo users: password123
        $defaultPassword = Hash::make('password123');

        DB::table('users')->insert([
            [
                'user_id' => 1,
                'email' => 'admin@geospace.com',
                'password_hash' => $defaultPassword,
                'role_id' => 1, // Admin
                'user_position' => 'System Administrator',
                'auth_provider' => null,
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'active_status' => 0,
                'avatar' => 'avatar.png',
                'dark_mode' => 0,
                'messenger_color' => null,
            ],
            [
                'user_id' => 29,
                'email' => 'sajib@gmail.com',
                'password_hash' => $defaultPassword,
                'role_id' => 2, // Freelancer
                'user_position' => 'Environmental Specialist',
                'auth_provider' => 'Manual',
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'active_status' => 0,
                'avatar' => 'avatar.png',
                'dark_mode' => 0,
                'messenger_color' => null,
            ],
            [
                'user_id' => 33,
                'email' => 'company@spacex.com',
                'password_hash' => $defaultPassword,
                'role_id' => 3, // Company
                'user_position' => 'CEO',
                'auth_provider' => 'Manual',
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'active_status' => 0,
                'avatar' => 'avatar.png',
                'dark_mode' => 0,
                'messenger_color' => null,
            ],
            [
                'user_id' => 34,
                'email' => 'test@newcompany2.com',
                'password_hash' => $defaultPassword,
                'role_id' => 3, // Company
                'user_position' => 'Company Representative',
                'auth_provider' => 'Manual',
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'active_status' => 0,
                'avatar' => 'avatar.png',
                'dark_mode' => 0,
                'messenger_color' => null,
            ],
        ]);
    }
}
