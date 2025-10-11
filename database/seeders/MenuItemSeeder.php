<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder populates the menu_items table with a hierarchical menu structure
     * for different user roles: Admin, Company, Freelancer, and Support.
     */
    public function run(): void
    {
        $menuItems = [
            // ============================================
            // ADMIN MENU ITEMS
            // ============================================

            // System Overview Parent
            [
                'menu_id' => 1,
                'parent_menu_id' => null,
                'menu_name' => 'System Overview',
                'menu_url' => null,
                'menu_icon' => 'system-overview',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 2,
                'parent_menu_id' => 1,
                'menu_name' => 'Total Active Users',
                'menu_url' => '/admin/active-users',
                'menu_icon' => null,
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 3,
                'parent_menu_id' => 1,
                'menu_name' => 'Contracts in Progress',
                'menu_url' => '/admin/contracts',
                'menu_icon' => null,
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 4,
                'parent_menu_id' => 1,
                'menu_name' => 'Approved Timesheets',
                'menu_url' => '/admin/approved-timesheets',
                'menu_icon' => null,
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 5,
                'parent_menu_id' => 1,
                'menu_name' => 'Dispute Tickets',
                'menu_url' => '/admin/dispute-tickets',
                'menu_icon' => null,
                'sort_order' => 4,
                'is_active' => 1
            ],
            [
                'menu_id' => 6,
                'parent_menu_id' => 1,
                'menu_name' => 'Platform Metrics',
                'menu_url' => '/admin/platform-metrics',
                'menu_icon' => null,
                'sort_order' => 5,
                'is_active' => 1
            ],

            // Timesheet
            [
                'menu_id' => 7,
                'parent_menu_id' => null,
                'menu_name' => 'Timesheet',
                'menu_url' => '/admin/approved-timesheets',
                'menu_icon' => 'timesheet',
                'sort_order' => 2,
                'is_active' => 1
            ],

            // User Management Parent
            [
                'menu_id' => 8,
                'parent_menu_id' => null,
                'menu_name' => 'User Management',
                'menu_url' => null,
                'menu_icon' => 'users',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 9,
                'parent_menu_id' => 8,
                'menu_name' => 'Verified Users',
                'menu_url' => '/admin/users/verified',
                'menu_icon' => null,
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 10,
                'parent_menu_id' => 8,
                'menu_name' => 'Pending Verifications',
                'menu_url' => '/admin/users/pending',
                'menu_icon' => null,
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 11,
                'parent_menu_id' => 8,
                'menu_name' => 'Suspended Accounts',
                'menu_url' => '/admin/users/suspended',
                'menu_icon' => null,
                'sort_order' => 4,
                'is_active' => 1
            ],

            // Project & Contract Parent
            [
                'menu_id' => 12,
                'parent_menu_id' => null,
                'menu_name' => 'Project & Contract',
                'menu_url' => null,
                'menu_icon' => 'project-contract',
                'sort_order' => 4,
                'is_active' => 1
            ],
            [
                'menu_id' => 13,
                'parent_menu_id' => 12,
                'menu_name' => 'Active Contracts',
                'menu_url' => '/admin/contracts',
                'menu_icon' => null,
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 14,
                'parent_menu_id' => 12,
                'menu_name' => 'Milestone progress',
                'menu_url' => '/admin/projects/milestones',
                'menu_icon' => null,
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 15,
                'parent_menu_id' => 12,
                'menu_name' => 'Platform Satisfaction',
                'menu_url' => '/admin/projects/satisfaction',
                'menu_icon' => 'star',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Support & Escalation Parent
            [
                'menu_id' => 16,
                'parent_menu_id' => null,
                'menu_name' => 'Support & Escalation',
                'menu_url' => null,
                'menu_icon' => 'customer-support',
                'sort_order' => 6,
                'is_active' => 1
            ],
            [
                'menu_id' => 17,
                'parent_menu_id' => 16,
                'menu_name' => 'Dispute Tickets',
                'menu_url' => '/admin/dispute-tickets',
                'menu_icon' => 'alert-triangle',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 18,
                'parent_menu_id' => 16,
                'menu_name' => 'Support Agents',
                'menu_url' => '/admin/support/agents',
                'menu_icon' => 'users',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 19,
                'parent_menu_id' => 16,
                'menu_name' => 'Live Chat',
                'menu_url' => '/admin/support/chat',
                'menu_icon' => 'message-circle',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 20,
                'parent_menu_id' => 16,
                'menu_name' => 'Video Chat',
                'menu_url' => '/admin/support/video-chat',
                'menu_icon' => 'video',
                'sort_order' => 4,
                'is_active' => 1
            ],

            // Financial Management Parent
            [
                'menu_id' => 21,
                'parent_menu_id' => null,
                'menu_name' => 'Financial Mangement',
                'menu_url' => null,
                'menu_icon' => 'dollar-sign',
                'sort_order' => 5,
                'is_active' => 1
            ],
            [
                'menu_id' => 22,
                'parent_menu_id' => 21,
                'menu_name' => 'Payment from Company',
                'menu_url' => '/admin/financial-management/payment-from-company',
                'menu_icon' => 'credit-card',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 23,
                'parent_menu_id' => 21,
                'menu_name' => 'Payment to Freelancer',
                'menu_url' => '/admin/financial-management/payment-to-freelancer',
                'menu_icon' => 'banknote',
                'sort_order' => 2,
                'is_active' => 1
            ],

            // Content Parent
            [
                'menu_id' => 26,
                'parent_menu_id' => null,
                'menu_name' => 'Content',
                'menu_url' => null,
                'menu_icon' => 'edit',
                'sort_order' => 8,
                'is_active' => 1
            ],
            [
                'menu_id' => 27,
                'parent_menu_id' => 26,
                'menu_name' => 'Blog Management',
                'menu_url' => '/admin/blogs',
                'menu_icon' => 'book-open',
                'sort_order' => 1,
                'is_active' => 1
            ],

            // ============================================
            // COMPANY MENU ITEMS
            // ============================================

            // Dashboard
            [
                'menu_id' => 28,
                'parent_menu_id' => null,
                'menu_name' => 'Dashboard',
                'menu_url' => '/company/home/current-projects',
                'menu_icon' => 'dashboard',
                'sort_order' => 1,
                'is_active' => 1
            ],

            // Projects Parent
            [
                'menu_id' => 29,
                'parent_menu_id' => null,
                'menu_name' => 'Projects',
                'menu_url' => null,
                'menu_icon' => 'briefcase',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 30,
                'parent_menu_id' => 29,
                'menu_name' => 'Current Projects',
                'menu_url' => '/company/home/current-projects',
                'menu_icon' => 'folder',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 31,
                'parent_menu_id' => 29,
                'menu_name' => 'Active Freelancers',
                'menu_url' => '/company/home/active-freelancers',
                'menu_icon' => 'users',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 32,
                'parent_menu_id' => 29,
                'menu_name' => 'Pending Timesheets',
                'menu_url' => '/company/home/pending-timesheet',
                'menu_icon' => 'clock',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Freelancers Parent
            [
                'menu_id' => 33,
                'parent_menu_id' => null,
                'menu_name' => 'Freelancers',
                'menu_url' => null,
                'menu_icon' => 'users',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 34,
                'parent_menu_id' => 33,
                'menu_name' => 'Profiles & Ratings',
                'menu_url' => '/company/freelancers/profiles',
                'menu_icon' => 'star',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 35,
                'parent_menu_id' => 33,
                'menu_name' => 'Monitor Performance',
                'menu_url' => '/company/freelancers/performance',
                'menu_icon' => 'activity',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 36,
                'parent_menu_id' => 33,
                'menu_name' => 'Feedback',
                'menu_url' => '/company/freelancers/feedback',
                'menu_icon' => 'message-square',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Jobs Parent
            [
                'menu_id' => 37,
                'parent_menu_id' => null,
                'menu_name' => 'Jobs',
                'menu_url' => null,
                'menu_icon' => 'briefcase',
                'sort_order' => 4,
                'is_active' => 1
            ],
            [
                'menu_id' => 38,
                'parent_menu_id' => 37,
                'menu_name' => 'Post New Opportunity',
                'menu_url' => '/company/jobs/new',
                'menu_icon' => 'plus',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 39,
                'parent_menu_id' => 37,
                'menu_name' => 'Pre-Certified Freelancers',
                'menu_url' => '/company/jobs/certified',
                'menu_icon' => 'award',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 40,
                'parent_menu_id' => 37,
                'menu_name' => 'Track Applications',
                'menu_url' => '/company/jobs/track',
                'menu_icon' => 'eye',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Profile Parent
            [
                'menu_id' => 41,
                'parent_menu_id' => null,
                'menu_name' => 'Profile',
                'menu_url' => null,
                'menu_icon' => 'user',
                'sort_order' => 5,
                'is_active' => 1
            ],
            [
                'menu_id' => 42,
                'parent_menu_id' => 41,
                'menu_name' => 'Update Profile',
                'menu_url' => '/company/profile/update',
                'menu_icon' => 'edit',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 43,
                'parent_menu_id' => 41,
                'menu_name' => 'List of Services',
                'menu_url' => '/company/profile/services',
                'menu_icon' => 'list',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 44,
                'parent_menu_id' => 41,
                'menu_name' => 'Portfolio Show',
                'menu_url' => '/company/profile/portfolio',
                'menu_icon' => 'image',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Financial Parent
            [
                'menu_id' => 45,
                'parent_menu_id' => null,
                'menu_name' => 'Financial',
                'menu_url' => null,
                'menu_icon' => 'dollar-sign',
                'sort_order' => 6,
                'is_active' => 1
            ],
            [
                'menu_id' => 46,
                'parent_menu_id' => 45,
                'menu_name' => 'Timesheet',
                'menu_url' => '/company/timesheet',
                'menu_icon' => 'clock',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 47,
                'parent_menu_id' => 45,
                'menu_name' => 'Upcoming Payments',
                'menu_url' => '/company/payments/upcoming',
                'menu_icon' => 'calendar',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 48,
                'parent_menu_id' => 45,
                'menu_name' => 'Invoices',
                'menu_url' => '/company/payments/invoices',
                'menu_icon' => 'file-text',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Support Parent
            [
                'menu_id' => 49,
                'parent_menu_id' => null,
                'menu_name' => 'Support',
                'menu_url' => null,
                'menu_icon' => 'headphones',
                'sort_order' => 7,
                'is_active' => 1
            ],
            [
                'menu_id' => 50,
                'parent_menu_id' => 49,
                'menu_name' => 'Compliance Documents',
                'menu_url' => '/company/support/compliance',
                'menu_icon' => 'file-check',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 51,
                'parent_menu_id' => 49,
                'menu_name' => 'Dispute Resolution',
                'menu_url' => '/company/support/disputes',
                'menu_icon' => 'alert-triangle',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 52,
                'parent_menu_id' => 49,
                'menu_name' => 'Support Panel',
                'menu_url' => '/company/support/panel',
                'menu_icon' => 'message-circle',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Notifications
            [
                'menu_id' => 53,
                'parent_menu_id' => null,
                'menu_name' => 'Notifications',
                'menu_url' => '/company/home/notifications',
                'menu_icon' => 'bell',
                'sort_order' => 8,
                'is_active' => 1
            ],

            // ============================================
            // FREELANCER MENU ITEMS
            // ============================================

            // Dashboard
            [
                'menu_id' => 54,
                'parent_menu_id' => null,
                'menu_name' => 'Dashboard',
                'menu_url' => '/freelancer/home/current-contracts',
                'menu_icon' => 'dashboard',
                'sort_order' => 1,
                'is_active' => 1
            ],

            // Work Parent
            [
                'menu_id' => 55,
                'parent_menu_id' => null,
                'menu_name' => 'Work',
                'menu_url' => null,
                'menu_icon' => 'briefcase',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 56,
                'parent_menu_id' => 55,
                'menu_name' => 'Current Contracts',
                'menu_url' => '/freelancer/home/current-contracts',
                'menu_icon' => 'file-text',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 57,
                'parent_menu_id' => 55,
                'menu_name' => 'Job Recommendations',
                'menu_url' => '/freelancer/home/job-recommendations',
                'menu_icon' => 'target',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 58,
                'parent_menu_id' => 55,
                'menu_name' => 'Earning Overview',
                'menu_url' => '/freelancer/home/earning-overview',
                'menu_icon' => 'trending-up',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // My Work Parent
            [
                'menu_id' => 59,
                'parent_menu_id' => null,
                'menu_name' => 'My Work',
                'menu_url' => null,
                'menu_icon' => 'work',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 60,
                'parent_menu_id' => 59,
                'menu_name' => 'My Contract',
                'menu_url' => '/freelancer/contracts',
                'menu_icon' => 'file-text',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 61,
                'parent_menu_id' => 59,
                'menu_name' => 'Products',
                'menu_url' => '/freelancer/products',
                'menu_icon' => 'package',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 62,
                'parent_menu_id' => 59,
                'menu_name' => 'Timesheet',
                'menu_url' => '/freelancer/timesheet',
                'menu_icon' => 'clock',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 63,
                'parent_menu_id' => 59,
                'menu_name' => 'Applications',
                'menu_url' => '/freelancer/applications',
                'menu_icon' => 'send',
                'sort_order' => 4,
                'is_active' => 1
            ],

            // Profile Parent
            [
                'menu_id' => 64,
                'parent_menu_id' => null,
                'menu_name' => 'Profile',
                'menu_url' => null,
                'menu_icon' => 'user',
                'sort_order' => 4,
                'is_active' => 1
            ],
            [
                'menu_id' => 65,
                'parent_menu_id' => 64,
                'menu_name' => 'Manage Profile',
                'menu_url' => '/freelancer/profile',
                'menu_icon' => 'edit',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 66,
                'parent_menu_id' => 64,
                'menu_name' => 'Recommendations',
                'menu_url' => '/freelancer/recommendations',
                'menu_icon' => 'thumbs-up',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 67,
                'parent_menu_id' => 64,
                'menu_name' => 'Reviews',
                'menu_url' => '/freelancer/reviews',
                'menu_icon' => 'star',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Earnings Parent
            [
                'menu_id' => 68,
                'parent_menu_id' => null,
                'menu_name' => 'Earnings',
                'menu_url' => null,
                'menu_icon' => 'dollar-sign',
                'sort_order' => 5,
                'is_active' => 1
            ],
            [
                'menu_id' => 69,
                'parent_menu_id' => 68,
                'menu_name' => 'Earnings Overview',
                'menu_url' => '/freelancer/earnings/overview',
                'menu_icon' => 'trending-up',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'menu_id' => 70,
                'parent_menu_id' => 68,
                'menu_name' => 'Earning Statement',
                'menu_url' => '/freelancer/earnings/statement',
                'menu_icon' => 'file-text',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'menu_id' => 71,
                'parent_menu_id' => 68,
                'menu_name' => 'Invoice & Pending Payments',
                'menu_url' => '/freelancer/earnings/invoices',
                'menu_icon' => 'credit-card',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'menu_id' => 72,
                'parent_menu_id' => 68,
                'menu_name' => 'Bank Information',
                'menu_url' => '/freelancer/earnings/bank-info',
                'menu_icon' => 'banknote',
                'sort_order' => 4,
                'is_active' => 1
            ],

            // Support
            [
                'menu_id' => 73,
                'parent_menu_id' => null,
                'menu_name' => 'Support',
                'menu_url' => '/freelancer/support',
                'menu_icon' => 'headphones',
                'sort_order' => 6,
                'is_active' => 1
            ],

            // ============================================
            // SUPPORT AGENT MENU ITEMS
            // ============================================

            // Dashboard
            [
                'menu_id' => 74,
                'parent_menu_id' => null,
                'menu_name' => 'Dashboard',
                'menu_url' => '/support/disputes',
                'menu_icon' => 'dashboard',
                'sort_order' => 1,
                'is_active' => 1
            ],

            // Disputes
            [
                'menu_id' => 75,
                'parent_menu_id' => null,
                'menu_name' => 'Disputes',
                'menu_url' => '/support/disputes',
                'menu_icon' => 'alert-triangle',
                'sort_order' => 2,
                'is_active' => 1
            ],

            // Live Chat
            [
                'menu_id' => 76,
                'parent_menu_id' => null,
                'menu_name' => 'Live Chat',
                'menu_url' => '/support/chat',
                'menu_icon' => 'message-circle',
                'sort_order' => 3,
                'is_active' => 1
            ],

            // Video Chat
            [
                'menu_id' => 77,
                'parent_menu_id' => null,
                'menu_name' => 'Video Chat',
                'menu_url' => '/support/video-chat',
                'menu_icon' => 'video',
                'sort_order' => 4,
                'is_active' => 1
            ],
        ];

        // Clear existing menu items
        DB::table('menu_items')->truncate();

        // Insert all menu items
        foreach ($menuItems as $menuItem) {
            DB::table('menu_items')->insert(array_merge($menuItem, [
                'created_at' => now(),
            ]));
        }

        $this->command->info('✅ Successfully seeded ' . count($menuItems) . ' menu items!');
        $this->command->info('📋 Menu structure:');
        $this->command->info('   - Admin: 27 menu items (System Overview, User Management, Projects, Financial, Support, Content)');
        $this->command->info('   - Company: 26 menu items (Dashboard, Projects, Freelancers, Jobs, Profile, Financial, Support)');
        $this->command->info('   - Freelancer: 20 menu items (Dashboard, Work, My Work, Profile, Earnings, Support)');
        $this->command->info('   - Support: 4 menu items (Dashboard, Disputes, Live Chat, Video Chat)');
    }
}
