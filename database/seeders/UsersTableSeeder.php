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

        // Insert users
        $users = [
            [
                'user_id' => 1,
                'email' => 'admin@geospace.com',
                'password_hash' => $defaultPassword,
                'role_id' => 1, // Admin
                'auth_provider' => null,
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 29,
                'email' => 'sajib@gmail.com',
                'password_hash' => $defaultPassword,
                'role_id' => 2, // Freelancer
                'auth_provider' => 'Manual',
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 33,
                'email' => 'company@spacex.com',
                'password_hash' => $defaultPassword,
                'role_id' => 3, // Company
                'auth_provider' => 'Manual',
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 34,
                'email' => 'test@newcompany2.com',
                'password_hash' => $defaultPassword,
                'role_id' => 3, // Company
                'auth_provider' => 'Manual',
                'is_active' => 1,
                'is_verified' => 1,
                'verification_status' => 'verified',
                'email_verified_at' => now(),
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Use insertOrIgnore to avoid duplicates
        DB::table('users')->insertOrIgnore($users);

        // Insert role-specific details

        // Admin details
        DB::table('admin_details')->insertOrIgnore([
            'user_id' => 1,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone' => '+1234567890',
            'profile_image' => 'avatar.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Freelancer details
        DB::table('freelancer_details')->insertOrIgnore([
            'user_id' => 29,
            'first_name' => 'Sajib',
            'last_name' => 'Ahmed',
            'phone' => '+1234567891',
            'address' => '123 Main St',
            'city' => 'Toronto',
            'state' => 'Ontario',
            'postal_code' => 'M1M 1M1',
            'country' => 'Canada',
            'designation' => 'Senior Environmental Specialist',
            'experience_years' => 5,
            'profile_image' => 'avatar.png',
            'bio' => 'Experienced environmental specialist with expertise in geological surveys.',
            'summary' => 'Passionate about environmental protection and geological analysis.',
            'linkedin_url' => 'https://linkedin.com/in/sajib-ahmed',
            'website_url' => null,
            'resume_or_cv' => null,
            'hourly_rate' => 75.00,
            'availability_status' => 'Available',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Company details
        DB::table('company_details')->insertOrIgnore([
            [
                'user_id' => 33,
                'company_name' => 'SpaceX',
                'contact_first_name' => 'Elon',
                'contact_last_name' => 'Musk',
                'contact_phone' => '+1234567892',
                'company_type' => 'Private',
                'industry' => 'Aerospace',
                'company_size' => '500+',
                'website' => 'https://spacex.com',
                'contact_linkedin' => 'https://linkedin.com/in/elon-musk',
                'description' => 'Space exploration and satellite technology company.',
                'founded_year' => 2002,
                'headquarters' => 'Hawthorne, CA',
                'address' => '1 Rocket Road',
                'city' => 'Hawthorne',
                'state' => 'California',
                'postal_code' => '90250',
                'country' => 'United States',
                'logo' => 'spacex_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 34,
                'company_name' => 'NewCompany2',
                'contact_first_name' => 'John',
                'contact_last_name' => 'Doe',
                'contact_phone' => '+1234567893',
                'company_type' => 'Private',
                'industry' => 'Technology',
                'company_size' => '11-50',
                'website' => 'https://newcompany2.com',
                'contact_linkedin' => 'https://linkedin.com/in/john-doe',
                'description' => 'Innovative technology solutions company.',
                'founded_year' => 2020,
                'headquarters' => 'Vancouver, BC',
                'address' => '456 Tech Street',
                'city' => 'Vancouver',
                'state' => 'British Columbia',
                'postal_code' => 'V6B 1A1',
                'country' => 'Canada',
                'logo' => 'newcompany_logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
