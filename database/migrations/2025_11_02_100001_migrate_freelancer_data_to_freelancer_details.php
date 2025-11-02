<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate freelancer data from user_details to freelancer_details
     */
    public function up(): void
    {
        // Get all freelancers (role_id = 2)
        $freelancers = DB::table('users')
            ->where('role_id', 2)
            ->pluck('user_id');

        foreach ($freelancers as $userId) {
            $userDetail = DB::table('user_details')
                ->where('user_id', $userId)
                ->first();

            if ($userDetail) {
                // Insert into freelancer_details
                DB::table('freelancer_details')->insert([
                    'user_id' => $userDetail->user_id,
                    'first_name' => $userDetail->first_name,
                    'last_name' => $userDetail->last_name,
                    'phone' => $userDetail->phone,
                    'address' => $userDetail->address,
                    'city' => $userDetail->city,
                    'state' => $userDetail->state,
                    'postal_code' => $userDetail->postal_code,
                    'country' => $userDetail->country,
                    'designation' => $userDetail->designation,
                    'experience_years' => $userDetail->experience_years,
                    'profile_image' => $userDetail->profile_image,
                    'bio' => $userDetail->bio,
                    'summary' => $userDetail->summary,
                    'linkedin_url' => $userDetail->linkedin_url,
                    'website_url' => $userDetail->website_url,
                    'resume_or_cv' => $userDetail->resume_or_cv,
                    'hourly_rate' => $userDetail->hourly_rate,
                    'availability_status' => $userDetail->availability_status ?? 'Available',
                    'created_at' => $userDetail->created_at,
                    'updated_at' => $userDetail->updated_at,
                ]);

                // Delete from user_details
                DB::table('user_details')
                    ->where('user_id', $userId)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all freelancer_details
        $freelancerDetails = DB::table('freelancer_details')->get();

        foreach ($freelancerDetails as $detail) {
            // Insert back into user_details
            DB::table('user_details')->insert([
                'user_id' => $detail->user_id,
                'first_name' => $detail->first_name,
                'last_name' => $detail->last_name,
                'phone' => $detail->phone,
                'address' => $detail->address,
                'city' => $detail->city,
                'state' => $detail->state,
                'postal_code' => $detail->postal_code,
                'country' => $detail->country,
                'designation' => $detail->designation,
                'experience_years' => $detail->experience_years,
                'profile_image' => $detail->profile_image,
                'bio' => $detail->bio,
                'summary' => $detail->summary,
                'linkedin_url' => $detail->linkedin_url,
                'website_url' => $detail->website_url,
                'resume_or_cv' => $detail->resume_or_cv,
                'hourly_rate' => $detail->hourly_rate,
                'availability_status' => $detail->availability_status,
                'created_at' => $detail->created_at,
                'updated_at' => $detail->updated_at,
            ]);

            // Delete from freelancer_details
            DB::table('freelancer_details')
                ->where('user_id', $detail->user_id)
                ->delete();
        }
    }
};
