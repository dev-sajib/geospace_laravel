<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            // Admin notifications
            [
                'notification_id' => 1,
                'user_id' => 1, // Admin
                'title' => 'New User Registration',
                'message' => 'A new freelancer has registered and is pending verification.',
                'type' => 'Info',
                'action_url' => '/admin/users/pending-verification',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(2),
            ],
            [
                'notification_id' => 2,
                'user_id' => 1, // Admin
                'title' => 'Timesheet Approval Required',
                'message' => '5 timesheets are pending approval and require your attention.',
                'type' => 'Warning',
                'action_url' => '/admin/timesheets/pending',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(1),
            ],
            [
                'notification_id' => 3,
                'user_id' => 1, // Admin
                'title' => 'Payment Request Processed',
                'message' => 'Payment request for $15,000 has been successfully processed.',
                'type' => 'Success',
                'action_url' => '/admin/payments',
                'is_read' => true,
                'read_at' => now()->subMinutes(30),
                'created_at' => now()->subHours(4),
            ],

            // Freelancer notifications
            [
                'notification_id' => 4,
                'user_id' => 2, // John Smith
                'title' => 'Timesheet Approved',
                'message' => 'Your timesheet for the week of January 15-21 has been approved.',
                'type' => 'Success',
                'action_url' => '/freelancer/timesheets/1',
                'is_read' => true,
                'read_at' => now()->subMinutes(15),
                'created_at' => now()->subHours(3),
            ],
            [
                'notification_id' => 5,
                'user_id' => 2, // John Smith
                'title' => 'New Project Assignment',
                'message' => 'You have been assigned to the Northern Gold Exploration Project.',
                'type' => 'Info',
                'action_url' => '/freelancer/projects/1',
                'is_read' => true,
                'read_at' => now()->subHours(1),
                'created_at' => now()->subDays(2),
            ],
            [
                'notification_id' => 6,
                'user_id' => 2, // John Smith
                'title' => 'Payment Received',
                'message' => 'Payment of $3,400 has been processed for your approved timesheet.',
                'type' => 'Success',
                'action_url' => '/freelancer/payments',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subMinutes(45),
            ],
            [
                'notification_id' => 7,
                'user_id' => 3, // Sarah Jones
                'title' => 'Contract Signed',
                'message' => 'Your contract with GeoData Analytics has been successfully signed.',
                'type' => 'Success',
                'action_url' => '/freelancer/contracts/2',
                'is_read' => true,
                'read_at' => now()->subHours(2),
                'created_at' => now()->subDays(1),
            ],
            [
                'notification_id' => 8,
                'user_id' => 3, // Sarah Jones
                'title' => 'Timesheet Under Review',
                'message' => 'Your timesheet for the week of February 1-7 is currently under review.',
                'type' => 'Info',
                'action_url' => '/freelancer/timesheets/3',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subMinutes(20),
            ],
            [
                'notification_id' => 9,
                'user_id' => 4, // Michael Brown
                'title' => 'Project Update',
                'message' => 'The Copper-Nickel Exploration project has been updated with new requirements.',
                'type' => 'Info',
                'action_url' => '/freelancer/projects/3',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subMinutes(10),
            ],
            [
                'notification_id' => 10,
                'user_id' => 5, // Emily Davis
                'title' => 'Timesheet Rejected',
                'message' => 'Your timesheet for the week of April 1-7 has been rejected. Please review the comments.',
                'type' => 'Error',
                'action_url' => '/freelancer/timesheets/7',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subMinutes(5),
            ],

            // Company notifications
            [
                'notification_id' => 11,
                'user_id' => 10, // Northern Mining Corp
                'title' => 'New Timesheet Submitted',
                'message' => 'John Smith has submitted a new timesheet for review.',
                'type' => 'Info',
                'action_url' => '/company/timesheets/11',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subMinutes(30),
            ],
            [
                'notification_id' => 12,
                'user_id' => 10, // Northern Mining Corp
                'title' => 'Project Milestone Completed',
                'message' => 'Milestone 1 of the Northern Gold Exploration Project has been completed.',
                'type' => 'Success',
                'action_url' => '/company/projects/1',
                'is_read' => true,
                'read_at' => now()->subHours(1),
                'created_at' => now()->subHours(2),
            ],
            [
                'notification_id' => 13,
                'user_id' => 11, // GeoData Analytics
                'title' => 'Contract Renewal Due',
                'message' => 'Your contract with Sarah Jones is due for renewal in 30 days.',
                'type' => 'Warning',
                'action_url' => '/company/contracts/2',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(6),
            ],
            [
                'notification_id' => 14,
                'user_id' => 12, // Exploration Corp International
                'title' => 'Payment Processed',
                'message' => 'Payment of $3,600 has been processed for Michael Brown\'s timesheet.',
                'type' => 'Success',
                'action_url' => '/company/payments',
                'is_read' => true,
                'read_at' => now()->subMinutes(45),
                'created_at' => now()->subHours(1),
            ],
            [
                'notification_id' => 15,
                'user_id' => 13, // Geo Services Ltd
                'title' => 'Project Deadline Approaching',
                'message' => 'The Environmental Impact Assessment project deadline is approaching in 2 weeks.',
                'type' => 'Warning',
                'action_url' => '/company/projects/4',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(12),
            ],
            [
                'notification_id' => 16,
                'user_id' => 14, // Mineral Solutions Inc
                'title' => 'New Freelancer Available',
                'message' => 'A new mining geologist with diamond exploration experience is now available.',
                'type' => 'Info',
                'action_url' => '/company/freelancers',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(8),
            ],
            [
                'notification_id' => 17,
                'user_id' => 15, // EarthTech Engineering
                'title' => 'Timesheet Approved',
                'message' => 'Jennifer Taylor\'s timesheet for the week of February 15-21 has been approved.',
                'type' => 'Success',
                'action_url' => '/company/timesheets/8',
                'is_read' => true,
                'read_at' => now()->subMinutes(20),
                'created_at' => now()->subHours(3),
            ],
            [
                'notification_id' => 18,
                'user_id' => 16, // Geology Consultants
                'title' => 'Project Update Required',
                'message' => 'Please provide an update on the Petroleum Geology Assessment project.',
                'type' => 'Info',
                'action_url' => '/company/projects/7',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(4),
            ],
            [
                'notification_id' => 19,
                'user_id' => 17, // Resource Exploration Group
                'title' => 'Contract Signed',
                'message' => 'Contract with John Smith for the REE Exploration project has been signed.',
                'type' => 'Success',
                'action_url' => '/company/contracts/8',
                'is_read' => true,
                'read_at' => now()->subHours(2),
                'created_at' => now()->subDays(1),
            ],

            // Support notifications
            [
                'notification_id' => 20,
                'user_id' => 18, // Support Agent 1
                'title' => 'New Dispute Ticket',
                'message' => 'A new dispute ticket has been assigned to you.',
                'type' => 'Info',
                'action_url' => '/support/disputes/1',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subMinutes(15),
            ],
            [
                'notification_id' => 21,
                'user_id' => 19, // Support Agent 2
                'title' => 'Dispute Resolved',
                'message' => 'Dispute ticket #123 has been successfully resolved.',
                'type' => 'Success',
                'action_url' => '/support/disputes/123',
                'is_read' => true,
                'read_at' => now()->subMinutes(30),
                'created_at' => now()->subHours(2),
            ],
        ];

        DB::table('notifications')->insert($notifications);
    }
}
