<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate personal data from users table to role-specific detail tables
     */
    public function up(): void
    {
        // Get all users with their personal data
        $users = DB::table('users')
            ->whereNotNull('first_name')
            ->orWhereNotNull('last_name')
            ->orWhereNotNull('phone')
            ->get();

        foreach ($users as $user) {
            switch ($user->role_id) {
                case 1: // Admin
                    // Insert into admin_details if not exists
                    DB::table('admin_details')->updateOrInsert(
                        ['user_id' => $user->user_id],
                        [
                            'first_name' => $user->first_name ?? '',
                            'last_name' => $user->last_name ?? '',
                            'phone' => $user->phone,
                            'profile_image' => null,
                            'created_at' => $user->created_at,
                            'updated_at' => now()
                        ]
                    );
                    break;

                case 2: // Freelancer
                    // Update freelancer_details if data exists in users but not in freelancer_details
                    $freelancerDetail = DB::table('freelancer_details')
                        ->where('user_id', $user->user_id)
                        ->first();

                    if ($freelancerDetail) {
                        // Update only if freelancer_details fields are empty
                        $updateData = [];
                        if (empty($freelancerDetail->first_name) && !empty($user->first_name)) {
                            $updateData['first_name'] = $user->first_name;
                        }
                        if (empty($freelancerDetail->last_name) && !empty($user->last_name)) {
                            $updateData['last_name'] = $user->last_name;
                        }
                        if (empty($freelancerDetail->phone) && !empty($user->phone)) {
                            $updateData['phone'] = $user->phone;
                        }

                        if (!empty($updateData)) {
                            $updateData['updated_at'] = now();
                            DB::table('freelancer_details')
                                ->where('user_id', $user->user_id)
                                ->update($updateData);
                        }
                    }
                    break;

                case 3: // Company
                    // Update company_details if data exists in users but not in company_details
                    $companyDetail = DB::table('company_details')
                        ->where('user_id', $user->user_id)
                        ->first();

                    if ($companyDetail) {
                        // Update only if company_details fields are empty
                        $updateData = [];
                        if (empty($companyDetail->contact_first_name) && !empty($user->first_name)) {
                            $updateData['contact_first_name'] = $user->first_name;
                        }
                        if (empty($companyDetail->contact_last_name) && !empty($user->last_name)) {
                            $updateData['contact_last_name'] = $user->last_name;
                        }
                        if (empty($companyDetail->contact_phone) && !empty($user->phone)) {
                            $updateData['contact_phone'] = $user->phone;
                        }

                        if (!empty($updateData)) {
                            $updateData['updated_at'] = now();
                            DB::table('company_details')
                                ->where('user_id', $user->user_id)
                                ->update($updateData);
                        }
                    }
                    break;

                case 4: // Support
                    // Insert into support_details if not exists
                    DB::table('support_details')->updateOrInsert(
                        ['user_id' => $user->user_id],
                        [
                            'first_name' => $user->first_name ?? '',
                            'last_name' => $user->last_name ?? '',
                            'phone' => $user->phone,
                            'profile_image' => null,
                            'created_at' => $user->created_at,
                            'updated_at' => now()
                        ]
                    );
                    break;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Copy data back from detail tables to users table

        // From admin_details
        $adminDetails = DB::table('admin_details')->get();
        foreach ($adminDetails as $detail) {
            DB::table('users')
                ->where('user_id', $detail->user_id)
                ->update([
                    'first_name' => $detail->first_name,
                    'last_name' => $detail->last_name,
                    'phone' => $detail->phone
                ]);
        }

        // From freelancer_details
        $freelancerDetails = DB::table('freelancer_details')->get();
        foreach ($freelancerDetails as $detail) {
            DB::table('users')
                ->where('user_id', $detail->user_id)
                ->update([
                    'first_name' => $detail->first_name,
                    'last_name' => $detail->last_name,
                    'phone' => $detail->phone
                ]);
        }

        // From company_details
        $companyDetails = DB::table('company_details')->get();
        foreach ($companyDetails as $detail) {
            DB::table('users')
                ->where('user_id', $detail->user_id)
                ->update([
                    'first_name' => $detail->contact_first_name,
                    'last_name' => $detail->contact_last_name,
                    'phone' => $detail->contact_phone
                ]);
        }

        // From support_details
        $supportDetails = DB::table('support_details')->get();
        foreach ($supportDetails as $detail) {
            DB::table('users')
                ->where('user_id', $detail->user_id)
                ->update([
                    'first_name' => $detail->first_name,
                    'last_name' => $detail->last_name,
                    'phone' => $detail->phone
                ]);
        }
    }
};
