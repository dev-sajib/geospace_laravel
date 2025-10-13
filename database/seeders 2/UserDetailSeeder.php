<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserDetailSeeder extends Seeder
{
    public function run(): void
    {
        $userDetails = [
            // Admin
            [
                'user_id' => 1,
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'phone' => '+1-800-555-0001',
                'city' => 'Toronto',
                'state' => 'Ontario',
                'country' => 'Canada',
                'hourly_rate' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Freelancers
            [
                'user_id' => 2,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'phone' => '+1-416-555-0101',
                'city' => 'Toronto',
                'state' => 'Ontario',
                'country' => 'Canada',
                'hourly_rate' => 85.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'first_name' => 'Sarah',
                'last_name' => 'Jones',
                'phone' => '+1-604-555-0102',
                'city' => 'Vancouver',
                'state' => 'British Columbia',
                'country' => 'Canada',
                'hourly_rate' => 90.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'phone' => '+1-403-555-0103',
                'city' => 'Calgary',
                'state' => 'Alberta',
                'country' => 'Canada',
                'hourly_rate' => 80.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'phone' => '+1-514-555-0104',
                'city' => 'Montreal',
                'state' => 'Quebec',
                'country' => 'Canada',
                'hourly_rate' => 85.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6,
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'phone' => '+1-416-555-0105',
                'city' => 'Toronto',
                'state' => 'Ontario',
                'country' => 'Canada',
                'hourly_rate' => 90.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7,
                'first_name' => 'Lisa',
                'last_name' => 'Martinez',
                'phone' => '+1-604-555-0106',
                'city' => 'Vancouver',
                'state' => 'British Columbia',
                'country' => 'Canada',
                'hourly_rate' => 95.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8,
                'first_name' => 'Robert',
                'last_name' => 'Anderson',
                'phone' => '+1-403-555-0107',
                'city' => 'Calgary',
                'state' => 'Alberta',
                'country' => 'Canada',
                'hourly_rate' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9,
                'first_name' => 'Jennifer',
                'last_name' => 'Taylor',
                'phone' => '+1-416-555-0108',
                'city' => 'Toronto',
                'state' => 'Ontario',
                'country' => 'Canada',
                'hourly_rate' => 75.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('user_details')->insert($userDetails);
    }
}
