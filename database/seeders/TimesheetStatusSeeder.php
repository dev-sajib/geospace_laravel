<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimesheetStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('timesheet_status')->insertOrIgnore([
            [
                'status_id' => 1,
                'status_name' => 'Draft',
                'status_description' => 'Timesheet is being created',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 2,
                'status_name' => 'Submitted',
                'status_description' => 'Timesheet has been submitted for review',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 3,
                'status_name' => 'Approved',
                'status_description' => 'Timesheet has been approved',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 4,
                'status_name' => 'Rejected',
                'status_description' => 'Timesheet has been rejected',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 5,
                'status_name' => 'Payment Requested',
                'status_description' => 'Payment has been requested for this timesheet',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 6,
                'status_name' => 'Paid',
                'status_description' => 'Payment has been completed for this timesheet',
                'is_active' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
