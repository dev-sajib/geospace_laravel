<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemsSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        DB::table( 'menu_items' )->insert( [
            // Admin Menu Items
            [
                'menu_id'        => 1,
                'parent_menu_id' => null,
                'menu_name'      => 'System Overview',
                'menu_url'     => null,
                'menu_icon'      => 'system-overview',
                'sort_order'     => 1,
                'is_active' => 1
            ],
            [
                'menu_id'        => 2,
                'parent_menu_id' => 1,
                'menu_name'      => 'Total Active Users',
                'menu_url'     => '/admin/active-users',
                'menu_icon'      => null,
                'sort_order'     => 1,
                'is_active' => 1
            ],
            [
                'menu_id'        => 3,
                'parent_menu_id' => 1,
                'menu_name'      => 'Contracts in Progress',
                'menu_url'     => '/admin/active-contracts',
                'menu_icon'      => null,
                'sort_order'     => 2,
                'is_active' => 1
            ],
            [
                'menu_id'        => 4,
                'parent_menu_id' => 1,
                'menu_name'      => 'Approved Timesheets',
                'menu_url'     => '/admin/approved-timesheets',
                'menu_icon'      => null,
                'sort_order'     => 3,
                'is_active' => 1
            ],
            [
                'menu_id'        => 5,
                'parent_menu_id' => 1,
                'menu_name'      => 'Dispute Tickets',
                'menu_url'     => '/admin/dispute-tickets',
                'menu_icon'      => null,
                'sort_order'     => 4,
                'is_active' => 1
            ],
            [
                'menu_id'        => 6,
                'parent_menu_id' => 1,
                'menu_name'      => 'Platform Metrics',
                'menu_url'     => '/admin/platform-metrics',
                'menu_icon'      => null,
                'sort_order'     => 5,
                'is_active' => 1
            ],

            //ADMIN - TIMESHEETS
            [
                'menu_id'        => 7,
                'parent_menu_id' => null,
                'menu_name'      => 'Timesheet',
                'menu_url'     => '/admin/approved-timesheet',
                'menu_icon'      => 'timesheet',
                'sort_order'     => 2,
                'is_active' => 1
            ],

            //User Management
            [
                'menu_id'        => 8,
                'parent_menu_id' => null,
                'menu_name'      => 'User Management',
                'menu_url'     => null,
                'menu_icon'      => 'users',
                'sort_order'     => 2,
                'is_active' => 1
            ],
            [
                'menu_id'        => 9,
                'parent_menu_id' => 8,
                'menu_name'      => 'Verified Users',
                'menu_url'     => '/admin/users/verified',
                'menu_icon'      => null,
                'sort_order'     => 1,
                'is_active' => 1
            ],
            [
                'menu_id'        => 10,
                'parent_menu_id' => 8,
                'menu_name'      => 'Pending Verifications',
                'menu_url'     => '/admin/users/pending',
                'menu_icon'      => null,
                'sort_order'     => 3,
                'is_active' => 1
            ],
            [
                'menu_id'        => 11,
                'parent_menu_id' => 8,
                'menu_name'      => 'Suspended Accounts',
                'menu_url'     => '/admin/users/suspended',
                'menu_icon'      => null,
                'sort_order'     => 4,
                'is_active' => 1
            ],
            [
                'menu_id'        => 12,
                'parent_menu_id' => null,
                'menu_name'      => 'Project & Contract',
                'menu_url'     => null,
                'menu_icon'      => 'project-contract',
                'sort_order'     => 3,
                'is_active' => 1
            ],
            [
                'menu_id'        => 13,
                'parent_menu_id' => 12,
                'menu_name'      => 'Active Contracts',
                'menu_url'     => '/admin/contracts',
                'menu_icon'      => null,
                'sort_order'     => 1,
                'is_active' => 1
            ],
            [
                'menu_id'        => 14,
                'parent_menu_id' => 12,
                'menu_name'      => 'Milestone progress',
                'menu_url'     => '/admin/projects/milestones',
                'menu_icon'      => null,
                'sort_order'     => 2,
                'is_active' => 1
            ],
            [
                'menu_id'        => 15,
                'parent_menu_id' => 12,
                'menu_name'      => 'Platform Satisfaction',
                'menu_url'     => '/admin/projects/satisfaction',
                'menu_icon'      => 'star',
                'sort_order'     => 3,
                'is_active' => 1
            ]
        ] );
    }
}
