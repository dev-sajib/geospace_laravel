<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    /**
     * Get user list for freelancer
     *
     * @return JsonResponse
     */
    public function userList(): JsonResponse {
        try {
            $users = DB::table( 'users as u' )
                       ->join( 'roles as r', 'u.role_id', '=', 'r.role_id' )
                       ->leftJoin( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                       ->leftJoin( 'company_details as cd', 'u.user_id', '=', 'cd.user_id' )
                       ->select(
                           'u.user_id',
                           'u.email',
                           'u.created_at',
                           'u.last_login',
                           'u.is_active',
                           'r.role_name',
                           'ud.first_name',
                           'ud.last_name',
                           'cd.company_name'
                       )
                       ->where( 'u.is_active', true )
                       ->where( 'u.is_verified', true )
                       ->orderBy( 'u.created_at', 'desc' )
                       ->get();

            // Transform data to match frontend expectations
            $transformedUsers = $users->map( function ( $user ) {
                return [
                    'UserName'       => trim( ( $user->first_name ?? '' ) . ' ' . ( $user->last_name ?? '' ) ) ?: ( $user->company_name ?? $user->email ),
                    'Role'           => $user->role_name ?? 'Unknown',
                    'Email'          => $user->email,
                    'Status'         => $user->is_active ? 'Active' : 'Inactive',
                    'JoinedDate'     => $user->created_at,
                    'LastActiveDate' => $user->last_login,
                    'user_id'        => $user->user_id
                ];
            } );

            return response()->json( $transformedUsers );

        } catch ( \Exception $e ) {
            return response()->json(
                MessageHelper::error( 'An error occurred: ' . $e->getMessage() ),
                500
            );
        }
    }

    /**
     * Get active contracts for freelancer
     *
     * @return JsonResponse
     */
    public function getActiveContracts(): JsonResponse {
        try {
            $freelancerId = Auth::id();

            $contracts = DB::table( 'contracts as c' )
                           ->join( 'projects as p', 'c.project_id', '=', 'p.project_id' )
                           ->join( 'company_details as cd', 'c.company_id', '=', 'cd.company_id' )
                           ->where( 'c.freelancer_id', $freelancerId )
                           ->where( 'c.status', 'Active' )
                           ->select(
                               'c.contract_id',
                               'c.contract_title',
                               'c.start_date',
                               'c.end_date',
                               'c.hourly_rate',
                               'c.total_amount',
                               'c.status',
                               'c.created_at',
                               'c.updated_at',
                               'p.project_title',
                               'cd.company_name'
                           )
                           ->orderBy( 'c.created_at', 'desc' )
                           ->get();

            return response()->json( [
                'Success' => true,
                'Message' => 'Active contracts retrieved successfully',
                'Data'    => $contracts
            ] );

        } catch ( \Exception $e ) {
            return response()->json( [
                'Success' => false,
                'Message' => 'Failed to retrieve active contracts',
                'Error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get job recommendations for freelancer
     *
     * @return JsonResponse
     */
    public function getJobRecommendations(): JsonResponse {
        try {
            $freelancerId = Auth::id();

            // Get freelancer's skills from user_details summary field
            $freelancerSkills = DB::table( 'user_details' )
                                  ->where( 'user_id', $freelancerId )
                                  ->value( 'summary' );

            $skillsArray = $freelancerSkills ? explode( ', ', $freelancerSkills ) : [];

            // Get projects that match freelancer skills or are in similar categories
            $projects = DB::table( 'projects as p' )
                          ->join( 'company_details as cd', 'p.company_id', '=', 'cd.company_id' )
                          ->where( 'p.status', 'Published' )
                          ->select(
                              'p.project_id',
                              'p.project_title',
                              'p.project_description',
                              'p.budget',
                              'p.currency',
                              'p.duration_weeks',
                              'p.skills_required',
                              'p.location',
                              'p.is_remote',
                              'p.deadline',
                              'p.created_at',
                              'cd.company_name'
                          )
                          ->orderBy( 'p.created_at', 'desc' )
                          ->limit( 10 )
                          ->get();

            return response()->json( [
                'Success' => true,
                'Message' => 'Job recommendations retrieved successfully',
                'Data'    => $projects
            ] );

        } catch ( \Exception $e ) {
            return response()->json( [
                'Success' => false,
                'Message' => 'Failed to retrieve job recommendations',
                'Error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get earnings overview for freelancer
     *
     * @return JsonResponse
     */
    public function getEarningsOverview(): JsonResponse {
        try {
            $freelancerId = Auth::id();
            $year         = request( 'year', date( 'Y' ) );
            $month        = request( 'month', date( 'F' ) );

            // Get monthly earnings data for the year
            $monthlyEarnings = DB::table( 'timesheets as t' )
                                 ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                                 ->where( 't.freelancer_id', $freelancerId )
                                 ->where( 'ts.status_name', 'Approved' )
                                 ->whereYear( 't.created_at', $year )
                                 ->select(
                                     DB::raw( 'MONTH(t.created_at) as month' ),
                                     DB::raw( 'SUM(t.total_amount) as total_earnings' )
                                 )
                                 ->groupBy( DB::raw( 'MONTH(t.created_at)' ) )
                                 ->get();

            // Create chart data for all 12 months
            $chartData  = [];
            $monthNames = [
                1  => 'January',
                2  => 'February',
                3  => 'March',
                4  => 'April',
                5  => 'May',
                6  => 'June',
                7  => 'July',
                8  => 'August',
                9  => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December'
            ];

            for ( $i = 1; $i <= 12; $i ++ ) {
                $monthEarning = $monthlyEarnings->where( 'month', $i )->first();
                $chartData[]  = [
                    'month'    => $monthNames[ $i ],
                    'earnings' => $monthEarning ? (float) $monthEarning->total_earnings : 0
                ];
            }

            // Get current month earnings
            $currentMonthEarnings = $monthlyEarnings->where( 'month', array_search( $month, $monthNames ) )->first();
            $currentEarnings      = $currentMonthEarnings ? (float) $currentMonthEarnings->total_earnings : 0;

            return response()->json( [
                'Success' => true,
                'Message' => 'Earnings overview retrieved successfully',
                'Data'    => [
                    'income'    => $currentEarnings,
                    'year'      => $year,
                    'month'     => $month,
                    'chartData' => $chartData
                ]
            ] );

        } catch ( \Exception $e ) {
            return response()->json( [
                'Success' => false,
                'Message' => 'Failed to retrieve earnings overview',
                'Error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get dashboard statistics for freelancer
     *
     * @return JsonResponse
     */
    public function getDashboardStats(): JsonResponse {
        try {
            $freelancerId = Auth::id();

            // Get active contracts count
            $activeContracts = DB::table( 'contracts' )
                                 ->where( 'freelancer_id', $freelancerId )
                                 ->where( 'status', 'Active' )
                                 ->count();

            $hourlyRate = DB::table( 'user_details' )
                            ->where( 'user_id', $freelancerId )
                            ->select( 'hourly_rate' )
                            ->get();


            // Get current balance (sum of approved timesheets not yet paid)
            $currentBalance = DB::table( 'timesheets as t' )
                                ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                                ->where( 't.freelancer_id', $freelancerId )
                                ->where( 'ts.status_name', 'Payment_Completed' )
                                ->whereNull( 't.payment_completed_at' )
                                ->sum( 't.total_amount' );

            $totalEarning = DB::table( 'timesheets as t' )
                              ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                              ->where( 't.freelancer_id', $freelancerId )
                              ->sum( 't.total_amount' );

            $pendingPayment = DB::table( 'timesheets as t' )
                                ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                                ->where( 't.freelancer_id', $freelancerId )
                                ->where( 'ts.status_name', 'Payment_Processing' )
                                ->sum( 't.total_amount' );

            // Get job recommendations count
            $recommendations = DB::table( 'projects as p' )
                                 ->join( 'company_details as cd', 'p.company_id', '=', 'cd.company_id' )
                                 ->where( 'p.status', 'Published' )
                                 ->count();

            // Get pending invoices count (timesheets awaiting payment)
            $pendingInvoices = DB::table( 'timesheets as t' )
                                 ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                                 ->where( 't.freelancer_id', $freelancerId )
                                 ->where( 'ts.status_name', 'Approved' )
                                 ->whereNotNull( 't.payment_requested_at' )
                                 ->whereNull( 't.payment_completed_at' )
                                 ->count();

            return response()->json( [
                'Success' => true,
                'Message' => 'Dashboard statistics retrieved successfully',
                'Data'    => [
                    'total_earning'    => (float) $totalEarning,
                    'active_contracts' => $activeContracts,
                    'current_balance'  => (float) $currentBalance,
                    'recommendations'  => $recommendations,
                    'pending_invoices' => $pendingInvoices,
                    'pending_payment'  => $pendingPayment,
                    'hourly_rate'      => $hourlyRate,
                ]
            ] );

        } catch ( \Exception $e ) {
            return response()->json( [
                'Success' => false,
                'Message' => 'Failed to retrieve dashboard statistics',
                'Error'   => $e->getMessage()
            ], 500 );
        }
    }
}
