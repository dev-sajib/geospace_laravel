<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\Notification;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
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
    /**
     * Get current project list for authenticated company
     * with progress calculation based on contract dates
     *
     * @return JsonResponse
     */
    public function currentProjectList(): JsonResponse
    {
        try {
            $user = Auth::user();

            // Get company_id from company_details table
            $companyDetails = DB::table('company_details')
                ->where('user_id', $user->user_id)
                ->first();

            if (!$companyDetails) {
                return response()->json(
                    MessageHelper::error('Company details not found'),
                    404
                );
            }

            $companyId = $companyDetails->company_id;

            // Get projects with contracts for this company
            $projects = DB::table('projects as p')
                ->leftJoin('contracts as c', function($join) {
                    $join->on('p.project_id', '=', 'c.project_id')
                         ->where('c.status', '=', 'Active');
                })
                ->leftJoin('company_details as cd', 'p.company_id', '=', 'cd.company_id')
                ->select(
                    'p.project_id',
                    'p.project_title',
                    'p.project_description',
                    'p.duration_weeks',
                    'p.status as project_status',
                    'p.updated_at',
                    'cd.logo',
                    'c.start_date',
                    'c.end_date'
                )
                ->where('p.company_id', $companyId)
                ->where('p.status', 'In Progress')
                ->orderBy('p.created_at', 'desc')
                ->get();

            // Format projects with progress calculation
            $formattedProjects = $projects->map(function ($project) {
                $progress = 0;
                $status = 'Medium Priority';

                if ($project->start_date && $project->duration_weeks) {
                    $startDate = \Carbon\Carbon::parse($project->start_date);
                    $durationDays = $project->duration_weeks * 7;
                    $endDate = $startDate->copy()->addDays($durationDays);
                    $today = \Carbon\Carbon::now();

                    // Calculate days elapsed and total days
                    $totalDays = $startDate->diffInDays($endDate);
                    $daysElapsed = $startDate->diffInDays($today);

                    // Calculate progress percentage
                    if ($totalDays > 0) {
                        $progress = min(100, round(($daysElapsed / $totalDays) * 100));
                    }

                    // Determine priority based on progress
                    if ($progress < 40) {
                        $status = 'Low Priority';
                    } elseif ($progress >= 40 && $progress < 75) {
                        $status = 'Medium Priority';
                    } else {
                        $status = 'High Priority';
                    }
                }

                return [
                    'project_id' => $project->project_id,
                    'title' => $project->project_title,
                    'icon' => $project->logo ? env('APP_URL') . '/' . $project->logo : '/images/Fictional-company-logo.png',
                    'lastUpdate' => $project->updated_at ? \Carbon\Carbon::parse($project->updated_at)->format('d F, Y') : 'N/A',
                    'progress' => $progress,
                    'status' => $status,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedProjects,
                'total' => $formattedProjects->count()
            ]);

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get active freelancer list
     *
     * @return JsonResponse
     */
    public function activeFreelancerList(): JsonResponse
    {
        try {
            $freelancers = DB::table('users as u')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('roles as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('contracts as c', function($join) {
                    $join->on('u.user_id', '=', 'c.freelancer_id')
                         ->where('c.status', '=', 'Active');
                })
                ->select(
                    'u.user_id',
                    'u.email',
                    'u.created_at',
                    'u.last_login',
                    'u.is_active',
                    'ud.first_name',
                    'ud.last_name',
                    'ud.profile_image',
                    'ud.hourly_rate',
                    'ud.availability_status',
                    'r.role_name',
                    DB::raw('COUNT(c.contract_id) as active_contracts')
                )
                ->where('u.is_active', true)
                ->where('u.is_verified', true)
                ->where('r.role_name', 'Freelancer')
                ->groupBy('u.user_id', 'u.email', 'u.created_at', 'u.last_login', 'u.is_active', 'ud.first_name', 'ud.last_name', 'ud.profile_image', 'ud.hourly_rate', 'ud.availability_status', 'r.role_name')
                ->orderBy('ud.first_name')
                ->get();

            // Transform data to match frontend expectations
            $transformedFreelancers = $freelancers->map(function ($freelancer) {
                return [
                    'UserName' => trim(($freelancer->first_name ?? '') . ' ' . ($freelancer->last_name ?? '')) ?: $freelancer->email,
                    'Role' => $freelancer->role_name ?? 'Freelancer',
                    'Email' => $freelancer->email,
                    'Status' => $freelancer->availability_status ?? 'Available',
                    'JoinedDate' => $freelancer->created_at,
                    'LastActiveDate' => $freelancer->last_login,
                    'user_id' => $freelancer->user_id,
                    'profile_image' => $freelancer->profile_image,
                    'hourly_rate' => $freelancer->hourly_rate,
                    'active_contracts' => $freelancer->active_contracts
                ];
            });

            if ($transformedFreelancers->count() > 0) {
                return response()->json($transformedFreelancers);
            } else {
                return response()->json(
                    MessageHelper::notFound('No active freelancers found'),
                    404
                );
            }

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get company pending timesheet list
     *
     * @return JsonResponse
     */
    public function companyPendingTimesheetList(): JsonResponse
    {
        try {
            $timesheets = DB::table('timesheets as t')
                ->join('contracts as c', 't.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 'c.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('company_details as cd', 'c.company_id', '=', 'cd.user_id')
                ->select(
                    't.*',
                    'c.contract_title',
                    'c.contract_value as hourly_rate',
                    'p.project_name as project_title',
                    'u.email as freelancer_email',
                    'ud.first_name',
                    'ud.last_name',
                    'cd.company_name'
                )
                ->where('t.status_id', 2) // Submitted status
                ->orderBy('t.submitted_at', 'desc')
                ->get();

            if ($timesheets->count() > 0) {
                return response()->json($timesheets);
            } else {
                return response()->json(
                    MessageHelper::notFound('No pending timesheets found'),
                    404
                );
            }

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get notification list
     *
     * @return JsonResponse
     */
    public function notificationList(): JsonResponse
    {
        try {
            $notifications = Notification::with('user:user_id,email')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            if ($notifications->count() > 0) {
                return response()->json($notifications);
            } else {
                return response()->json(
                    MessageHelper::notFound('No notifications found'),
                    404
                );
            }

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get company dashboard statistics
     *
     * @return JsonResponse
     */
    public function dashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_projects' => Project::count(),
                'active_projects' => Project::where('status', 'In Progress')->count(),
                'completed_projects' => Project::where('status', 'Completed')->count(),
                'total_freelancers' => User::whereHas('role', function($query) {
                    $query->where('role_name', 'Freelancer');
                })->where('is_active', true)->count(),
                'pending_timesheets' => Timesheet::where('status_id', 2)->count(),
                'approved_timesheets' => Timesheet::where('status_id', 4)->count(),
                'recent_activities' => DB::table('activity_logs')
                    ->join('users', 'activity_logs.user_id', '=', 'users.user_id')
                    ->select('activity_logs.*', 'users.email')
                    ->orderBy('activity_logs.created_at', 'desc')
                    ->limit(10)
                    ->get()
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get update profile list
     *
     * @return JsonResponse
     */
    public function updateProfileList(): JsonResponse
    {
        try {
            $users = DB::table('users as u')
                ->join('roles as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->leftJoin('company_details as cd', 'u.user_id', '=', 'cd.user_id')
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
                ->where('u.is_active', true)
                ->where('u.is_verified', true)
                ->orderBy('u.created_at', 'desc')
                ->get();

            // Transform data to match frontend expectations
            $transformedUsers = $users->map(function ($user) {
                return [
                    'UserName' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->company_name ?? $user->email),
                    'Role' => $user->role_name ?? 'Unknown',
                    'Email' => $user->email,
                    'Status' => $user->is_active ? 'Active' : 'Inactive',
                    'JoinedDate' => $user->created_at,
                    'LastActiveDate' => $user->last_login,
                    'user_id' => $user->user_id
                ];
            });

            return response()->json($transformedUsers);

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Create profile services
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createProfileServices(Request $request): JsonResponse
    {
        try {
            // This would typically create services for a company profile
            // For now, return a success message
            return response()->json(
                MessageHelper::success('Profile services created successfully')
            );

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }
}
