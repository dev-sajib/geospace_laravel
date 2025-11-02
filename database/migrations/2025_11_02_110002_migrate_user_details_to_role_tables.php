<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate data from user_details to appropriate role-based tables
     */
    public function up(): void
    {
        // Get all user_details records
        $userDetails = DB::table('user_details')->get();

        foreach ($userDetails as $detail) {
            // Get user role
            $user = DB::table('users')->where('user_id', $detail->user_id)->first();

            if (!$user) continue;

            switch ($user->role_id) {
                case 1: // Admin
                case 4: // Support
                    // Migrate to users table
                    DB::table('users')
                        ->where('user_id', $detail->user_id)
                        ->update([
                            'first_name' => $detail->first_name,
                            'last_name' => $detail->last_name,
                            'phone' => $detail->phone,
                            'updated_at' => now()
                        ]);
                    break;

                case 3: // Company
                    // Migrate to company_details
                    DB::table('company_details')
                        ->where('user_id', $detail->user_id)
                        ->update([
                            'contact_first_name' => $detail->first_name,
                            'contact_last_name' => $detail->last_name,
                            'contact_phone' => $detail->phone,
                            'address' => $detail->address,
                            'city' => $detail->city,
                            'state' => $detail->state,
                            'postal_code' => $detail->postal_code,
                            'country' => $detail->country,
                            'updated_at' => now()
                        ]);
                    break;

                // Freelancers (role_id = 2) already migrated in previous migration
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore data from role tables back to user_details
        // Get all users
        $users = DB::table('users')->get();

        foreach ($users as $user) {
            switch ($user->role_id) {
                case 1: // Admin
                case 4: // Support
                    if ($user->first_name || $user->last_name || $user->phone) {
                        DB::table('user_details')->updateOrInsert(
                            ['user_id' => $user->user_id],
                            [
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'phone' => $user->phone,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );
                    }
                    break;

                case 3: // Company
                    $company = DB::table('company_details')->where('user_id', $user->user_id)->first();
                    if ($company) {
                        DB::table('user_details')->updateOrInsert(
                            ['user_id' => $user->user_id],
                            [
                                'first_name' => $company->contact_first_name,
                                'last_name' => $company->contact_last_name,
                                'phone' => $company->contact_phone,
                                'address' => $company->address,
                                'city' => $company->city,
                                'state' => $company->state,
                                'postal_code' => $company->postal_code,
                                'country' => $company->country,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );
                    }
                    break;
            }
        }
    }
};
