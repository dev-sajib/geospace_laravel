<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'notification_id' => 1,
                'user_id' => 10,
                'title' => 'New Timesheet Submitted',
                'message' => 'John Smith has submitted a timesheet for review',
                'type' => 'Info',
                'action_url' => '/company/home/pending-timesheet',
                'is_read' => 0,
                'read_at' => null,
                'created_at' => '2025-10-07 18:48:50'
            ],
            [
                'notification_id' => 2,
                'user_id' => 11,
                'title' => 'Timesheet Rejected',
                'message' => 'Your timesheet for Sept 22-28 has been rejected. Please review comments and resubmit.',
                'type' => 'Warning',
                'action_url' => '/freelancer/timesheet',
                'is_read' => 0,
                'read_at' => null,
                'created_at' => '2025-10-07 18:48:50'
            ],
            [
                'notification_id' => 3,
                'user_id' => 5,
                'title' => 'Timesheet Approved',
                'message' => 'Your timesheet for Sept 8-14 has been approved!',
                'type' => 'Success',
                'action_url' => '/freelancer/timesheet',
                'is_read' => 1,
                'read_at' => null,
                'created_at' => '2025-10-07 18:48:50'
            ],
            [
                'notification_id' => 4,
                'user_id' => 8,
                'title' => 'Payment Completed',
                'message' => 'Payment of $4600.00 has been transferred to your account',
                'type' => 'Success',
                'action_url' => '/freelancer/earnings/overview',
                'is_read' => 1,
                'read_at' => null,
                'created_at' => '2025-10-07 18:48:50'
            ],
            [
                'notification_id' => 5,
                'user_id' => 1,
                'title' => 'Payment Request',
                'message' => 'David Wilson has requested payment for timesheet INV-2025-002',
                'type' => 'Info',
                'action_url' => '/admin/financial-management/payment-to-freelancer',
                'is_read' => 0,
                'read_at' => null,
                'created_at' => '2025-10-07 18:48:50'
            ]
        ]);
    }
}
