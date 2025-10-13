<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FreelancerEarningsSeeder extends Seeder
{
    public function run()
    {
        DB::table('freelancer_earnings')->insert([
            [
                'earning_id' => 1,
                'freelancer_id' => 5,
                'total_earned' => 3400.00,
                'pending_amount' => 3400.00,
                'completed_amount' => 0.00,
                'total_projects' => 1,
                'total_timesheets' => 1,
                'last_payment_date' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
        ]);
    }
}
