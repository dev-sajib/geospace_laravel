<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DashboardStatsController extends Controller
{
    /**
     * Get all dashboard statistics
     * GET /api/v1/admin/dashboard/stats
     */
    public function getDashboardStats()
    {
        try {
            // 1. Total Active Users (verified and active users)
            $totalActiveUsers = DB::table('users')
                ->where('is_active', true)
                ->where('is_verified', true)
                ->count();

            // 2. Contracts in Progress (Active status contracts)
            $contractsInProgress = DB::table('contracts')
                ->where('status', 'Active')
                ->count();

            // 3. Pending Timesheets (status_id = 1 which is 'Pending') - FIXED
            $pendingTimesheets = DB::table('timesheets')
                ->where('status_id', 1)  // FIXED: Was 2, now 1
                ->count();

            // 4. Open Dispute Tickets (status_id = 1 which is 'Open')
            $openDisputeTickets = DB::table('dispute_tickets')
                ->where('status_id', 1)
                ->count();

            // 5. Weekly Visitors Data (Platform Metrics)
            $weeklyVisitors = DB::table('visitor_logs')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 7 DAY)'))
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date', 'desc')
                ->limit(7)
                ->get();

            $totalWeeklyVisitors = $weeklyVisitors->sum('count');

            return response()->json([
                'success' => true,
                'message' => 'Dashboard statistics retrieved successfully',
                'data' => [
                    'total_active_users' => $totalActiveUsers,
                    'contracts_in_progress' => $contractsInProgress,
                    'pending_timesheets' => $pendingTimesheets,
                    'open_dispute_tickets' => $openDisputeTickets,
                    'weekly_visitors' => $totalWeeklyVisitors,
                    'weekly_visitors_data' => $weeklyVisitors
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}