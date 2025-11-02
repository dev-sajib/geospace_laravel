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
                'status_name' => 'Pending',
                'status_description' => 'Timesheet submitted and waiting for company review',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 2,
                'status_name' => 'Submitted',
                'status_description' => 'Timesheet approved by company',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 3,
                'status_name' => 'Approved',
                'status_description' => 'Timesheet rejected by company',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 4,
                'status_name' => 'Rejected',
                'status_description' => 'Timesheet resubmitted after rejection',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 5,
                'status_name' => 'Payment_Requested',
                'status_description' => 'Freelancer has requested payment',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 6,
                'status_name' => 'Payment_Processing',
                'status_description' => 'Admin is processing the payment',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 7,
                'status_name' => 'Payment_Completed',
                'status_description' => 'Payment completed successfully',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
        ]);
    }
}
