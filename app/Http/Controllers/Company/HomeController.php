<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\Notification;
use App\Models\CompanyDetail;
use App\Models\UserDetail;
use App\Models\Contract;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
                ->leftJoin('freelancer_details as fd', 'u.user_id', '=', 'fd.user_id')
                ->leftJoin('company_details as cd', 'u.user_id', '=', 'cd.user_id')
                ->leftJoin('admin_details as ad', 'u.user_id', '=', 'ad.user_id')
                ->leftJoin('support_details as sd', 'u.user_id', '=', 'sd.user_id')
                ->select(
                    'u.user_id',
                    'u.email',
                    'u.created_at',
                    'u.last_login',
                    'u.is_active',
                    'r.role_name',
                    DB::raw('COALESCE(fd.first_name, cd.contact_first_name, ad.first_name, sd.first_name) as first_name'),
                    DB::raw('COALESCE(fd.last_name, cd.contact_last_name, ad.last_name, sd.last_name) as last_name'),
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

    /**
     * Get company profile information
     *
     * @return JsonResponse
     */
    public function getCompanyProfile(): JsonResponse
    {
        try {
            $user = Auth::user();

            // Get company details
            $companyDetails = CompanyDetail::where('user_id', $user->user_id)->first();

            // Get user details (for contact person information)
            $userDetails = UserDetail::where('user_id', $user->user_id)->first();

            if (!$companyDetails) {
                return response()->json([
                    'Success' => false,
                    'Message' => 'Company profile not found. Please complete your profile setup.',
                    'Data' => null
                ], 404);
            }

            // Build contact person name, removing default placeholders
            $contactPersonName = null;
            if ($userDetails) {
                $firstName = ($userDetails->first_name && $userDetails->first_name !== 'N/A') ? $userDetails->first_name : '';
                $lastName = ($userDetails->last_name && $userDetails->last_name !== '-') ? $userDetails->last_name : '';
                $contactPersonName = trim($firstName . ' ' . $lastName) ?: null;
            }

            $profileData = [
                'company_id' => $companyDetails->company_id,
                'company_name' => $companyDetails->company_name,
                'company_type' => $companyDetails->company_type,
                'industry' => $companyDetails->industry,
                'company_size' => $companyDetails->company_size,
                'website' => $companyDetails->website,
                'description' => $companyDetails->description,
                'founded_year' => $companyDetails->founded_year,
                'headquarters' => $companyDetails->headquarters,
                'logo' => $companyDetails->logo, // Return just the path, not full URL
                'user_email' => $user->email,
                'contact_person_name' => $contactPersonName,
                'designation' => $userDetails->designation ?? $user->user_position ?? null,
                'phone' => $userDetails->phone ?? null,
                'city' => $userDetails->city ?? null,
                'country' => $userDetails->country ?? null,
            ];

            return response()->json([
                'Success' => true,
                'Message' => 'Company profile retrieved successfully',
                'Data' => $profileData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'Success' => false,
                'Message' => 'Failed to retrieve company profile',
                'Error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update company profile information
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCompanyProfile(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            // Validation rules
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'industry' => 'nullable|string|max:100',
                'company_type' => 'nullable|string|max:100',
                'company_size' => 'nullable|in:1-10,11-50,51-200,201-500,500+',
                'website' => 'nullable|url|max:500',
                'description' => 'nullable|string',
                'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
                'headquarters' => 'nullable|string|max:255',
                'logo' => 'nullable|string|max:500',
                'contact_person_name' => 'nullable|string|max:200',
                'designation' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'Success' => false,
                    'Message' => 'Validation failed',
                    'Errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Update or create company details
            $companyDetails = CompanyDetail::updateOrCreate(
                ['user_id' => $user->user_id],
                [
                    'company_name' => $request->company_name,
                    'company_type' => $request->company_type,
                    'industry' => $request->industry,
                    'company_size' => $request->company_size,
                    'website' => $request->website,
                    'description' => $request->description,
                    'founded_year' => $request->founded_year,
                    'headquarters' => $request->headquarters,
                    'logo' => $request->logo,
                ]
            );

            // Update user details if provided
            if ($request->has('contact_person_name') || $request->has('designation') || $request->has('phone') || $request->has('city') || $request->has('country')) {
                $nameParts = explode(' ', $request->contact_person_name ?? '', 2);
                $firstName = $nameParts[0] ?? 'N/A';
                $lastName = $nameParts[1] ?? '-';

                // Build update data with required fields
                $userDetailsData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ];

                // Add optional fields only if they have values
                if ($request->filled('designation')) {
                    $userDetailsData['designation'] = $request->designation;
                }
                if ($request->filled('phone')) {
                    $userDetailsData['phone'] = $request->phone;
                }
                if ($request->filled('city')) {
                    $userDetailsData['city'] = $request->city;
                }
                if ($request->filled('country')) {
                    $userDetailsData['country'] = $request->country;
                }

                UserDetail::updateOrCreate(
                    ['user_id' => $user->user_id],
                    $userDetailsData
                );
            }

            // Update user position if designation is provided
            if ($request->has('designation')) {
                $user->user_position = $request->designation;
                $user->save();
            }

            DB::commit();

            return response()->json([
                'Success' => true,
                'Message' => 'Company profile updated successfully',
                'Data' => [
                    'company_id' => $companyDetails->company_id,
                    'company_name' => $companyDetails->company_name,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'Success' => false,
                'Message' => 'Failed to update company profile',
                'Error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new project/opportunity
     * POST /api/v1/company/CreateProject
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createProject(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_title' => 'required|string|max:255',
                'project_description' => 'required|string',
                'project_type' => 'nullable|string|max:100',
                'budget' => 'nullable|numeric|min:0',
                'currency' => 'nullable|string|max:3',
                'duration_weeks' => 'nullable|integer|min:1',
                'skills_required' => 'nullable|array',
                'skills_required.*' => 'string|max:100',
                'location' => 'nullable|string|max:255',
                'is_remote' => 'nullable|boolean',
                'deadline' => 'nullable|date|after:today'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $user = Auth::user();
            $company = CompanyDetail::where('user_id', $user->user_id)->first();

            if (!$company) {
                return response()->json(
                    MessageHelper::error('Company profile not found'),
                    404
                );
            }

            DB::beginTransaction();

            try {
                $budget = $request->budget;
                $project = Project::create([
                    'company_id' => $company->company_id,
                    'project_title' => $request->project_title,
                    'project_description' => $request->project_description,
                    'project_type' => $request->project_type,
                    'budget' => $budget,
                    'currency' => $request->currency ?? 'CAD',
                    'duration_weeks' => $request->duration_weeks,
                    'status' => 'Published',
                    'skills_required' => $request->skills_required,
                    'location' => $request->location,
                    'is_remote' => $request->is_remote ?? false,
                    'deadline' => $request->deadline
                ]);

                DB::commit();

                return response()->json(
                    MessageHelper::success('Project created successfully', [
                        'project_id' => $project->project_id,
                        'project_title' => $project->project_title,
                        'status' => $project->status
                    ])
                );

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Project creation error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to create project: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get all projects for the authenticated company
     * GET /api/v1/company/GetProjects
     *
     * @return JsonResponse
     */
    public function getProjects(): JsonResponse
    {
        try {
            $user = Auth::user();
            $company = CompanyDetail::where('user_id', $user->user_id)->first();

            if (!$company) {
                return response()->json(
                    MessageHelper::error('Company profile not found'),
                    404
                );
            }

            $projects = Project::where('company_id', $company->company_id)
                ->orderBy('created_at', 'desc')
                ->get();

            $formattedProjects = $projects->map(function ($project) {
                return [
                    'project_id' => $project->project_id,
                    'project_title' => $project->project_title,
                    'project_description' => $project->project_description,
                    'project_type' => $project->project_type,
                    'budget' => $project->budget??0,
                    'currency' => $project->currency,
                    'duration_weeks' => $project->duration_weeks,
                    'status' => $project->status,
                    'skills_required' => $project->skills_required,
                    'location' => $project->location,
                    'is_remote' => $project->is_remote,
                    'deadline' => $project->deadline,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at
                ];
            });

            return response()->json(
                MessageHelper::success('Projects retrieved successfully', [
                    'projects' => $formattedProjects,
                    'total' => $formattedProjects->count()
                ])
            );

        } catch (\Exception $e) {
            Log::error('Get projects error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve projects: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get completed projects with contract details for feedback
     * GET /api/v1/company/GetCompletedProjects
     *
     * @return JsonResponse
     */
    public function getCompletedProjects(): JsonResponse
    {
        try {
            $user = Auth::user();
            $company = CompanyDetail::where('user_id', $user->user_id)->first();

            if (!$company) {
                return response()->json(
                    MessageHelper::error('Company profile not found'),
                    404
                );
            }

            // Get completed contracts with project and freelancer details
            $completedContracts = DB::table('contracts as c')
                ->join('projects as p', 'c.project_id', '=', 'p.project_id')
                ->join('users as u', 'c.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->leftJoin('feedback as f', 'c.contract_id', '=', 'f.contract_id')
                ->select(
                    'c.contract_id',
                    'c.project_id',
                    'c.freelancer_id',
                    'p.project_title',
                    'u.email as freelancer_email',
                    'ud.first_name',
                    'ud.last_name',
                    'ud.linkedin_url',
                    'f.feedback_id'
                )
                ->where('c.company_id', $company->company_id)
                ->where('c.status', 'Completed')
                ->whereNull('f.feedback_id') // Only show contracts without feedback
                ->orderBy('c.updated_at', 'desc')
                ->get();

            $formattedContracts = $completedContracts->map(function ($contract) {
                return [
                    'contract_id' => $contract->contract_id,
                    'project_id' => $contract->project_id,
                    'project_title' => $contract->project_title,
                    'freelancer_id' => $contract->freelancer_id,
                    'freelancer_name' => trim($contract->first_name . ' ' . $contract->last_name),
                    'freelancer_email' => $contract->freelancer_email,
                    'linkedin_url' => $contract->linkedin_url,
                ];
            });

            return response()->json(
                MessageHelper::success('Completed projects retrieved successfully', [
                    'projects' => $formattedContracts,
                    'total' => $formattedContracts->count()
                ])
            );

        } catch (\Exception $e) {
            Log::error('Get completed projects error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve completed projects: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Submit feedback for a completed contract
     * POST /api/v1/company/SubmitFeedback
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submitFeedback(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'contract_id' => 'required|integer|exists:contracts,contract_id',
                'attendance_rating' => 'required|integer|min:1|max:5',
                'attendance_comment' => 'nullable|string',
                'work_quality_rating' => 'required|integer|min:1|max:5',
                'work_quality_comment' => 'nullable|string',
                'execution_speed_rating' => 'required|integer|min:1|max:5',
                'execution_speed_comment' => 'nullable|string',
                'adaptability_rating' => 'required|integer|min:1|max:5',
                'adaptability_comment' => 'nullable|string',
                'general_feedback_rating' => 'required|integer|min:1|max:5',
                'general_feedback_comment' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $user = Auth::user();
            $company = CompanyDetail::where('user_id', $user->user_id)->first();

            if (!$company) {
                return response()->json(
                    MessageHelper::error('Company profile not found'),
                    404
                );
            }

            // Verify the contract belongs to this company and is completed
            $contract = Contract::where('contract_id', $request->contract_id)
                ->where('company_id', $company->company_id)
                ->where('status', 'Completed')
                ->first();

            if (!$contract) {
                return response()->json(
                    MessageHelper::error('Contract not found or not eligible for feedback'),
                    404
                );
            }

            // Check if feedback already exists for this contract
            $existingFeedback = DB::table('feedback')
                ->where('contract_id', $request->contract_id)
                ->first();

            if ($existingFeedback) {
                return response()->json(
                    MessageHelper::error('Feedback has already been submitted for this contract'),
                    409
                );
            }

            DB::beginTransaction();

            try {
                // Insert feedback (use user_id for company_id since it references users table)
                DB::table('feedback')->insert([
                    'contract_id' => $contract->contract_id,
                    'project_id' => $contract->project_id,
                    'company_id' => $user->user_id,  // Use user_id instead of company->company_id
                    'freelancer_id' => $contract->freelancer_id,
                    'attendance_rating' => $request->attendance_rating,
                    'attendance_comment' => $request->attendance_comment,
                    'work_quality_rating' => $request->work_quality_rating,
                    'work_quality_comment' => $request->work_quality_comment,
                    'execution_speed_rating' => $request->execution_speed_rating,
                    'execution_speed_comment' => $request->execution_speed_comment,
                    'adaptability_rating' => $request->adaptability_rating,
                    'adaptability_comment' => $request->adaptability_comment,
                    'general_feedback_rating' => $request->general_feedback_rating,
                    'general_feedback_comment' => $request->general_feedback_comment,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();

                return response()->json(
                    MessageHelper::success('Feedback submitted successfully', [
                        'contract_id' => $contract->contract_id,
                        'project_title' => $contract->contract_title
                    ])
                );

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Submit feedback error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to submit feedback: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get all feedback submitted by the company
     * GET /api/v1/company/GetFeedbackList
     *
     * @return JsonResponse
     */
    public function getFeedbackList(): JsonResponse
    {
        try {
            $user = Auth::user();

            // Get all feedback submitted by this company with freelancer details
            $feedbackList = DB::table('feedback as f')
                ->join('contracts as c', 'f.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 'f.project_id', '=', 'p.project_id')
                ->join('users as u', 'f.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->select(
                    'f.feedback_id',
                    'f.contract_id',
                    'f.project_id',
                    'f.freelancer_id',
                    'f.attendance_rating',
                    'f.attendance_comment',
                    'f.work_quality_rating',
                    'f.work_quality_comment',
                    'f.execution_speed_rating',
                    'f.execution_speed_comment',
                    'f.adaptability_rating',
                    'f.adaptability_comment',
                    'f.general_feedback_rating',
                    'f.general_feedback_comment',
                    'f.created_at',
                    'p.project_title',
                    'u.email as freelancer_email',
                    'ud.first_name',
                    'ud.last_name'
                )
                ->where('f.company_id', $user->user_id)
                ->orderBy('f.created_at', 'desc')
                ->get();

            $formattedFeedback = $feedbackList->map(function ($feedback) {
                return [
                    'id' => $feedback->feedback_id,
                    'freelancerName' => trim($feedback->first_name . ' ' . $feedback->last_name),
                    'freelancer_email' => $feedback->freelancer_email,
                    'project_title' => $feedback->project_title,
                    'attendance' => $feedback->attendance_rating,
                    'workQuality' => $feedback->work_quality_rating,
                    'executionSpeed' => $feedback->execution_speed_rating,
                    'adaptability' => $feedback->adaptability_rating,
                    'generalFeedback' => $feedback->general_feedback_rating,
                    'feedbackDetails' => [
                        'attendance' => [
                            'rating' => $feedback->attendance_rating,
                            'comment' => $feedback->attendance_comment ?? 'No comment provided'
                        ],
                        'workQuality' => [
                            'rating' => $feedback->work_quality_rating,
                            'comment' => $feedback->work_quality_comment ?? 'No comment provided'
                        ],
                        'executionSpeed' => [
                            'rating' => $feedback->execution_speed_rating,
                            'comment' => $feedback->execution_speed_comment ?? 'No comment provided'
                        ],
                        'adaptability' => [
                            'rating' => $feedback->adaptability_rating,
                            'comment' => $feedback->adaptability_comment ?? 'No comment provided'
                        ],
                        'generalFeedback' => [
                            'rating' => $feedback->general_feedback_rating,
                            'comment' => $feedback->general_feedback_comment ?? 'No comment provided'
                        ]
                    ],
                    'created_at' => $feedback->created_at
                ];
            });

            return response()->json(
                MessageHelper::success('Feedback list retrieved successfully', [
                    'feedback' => $formattedFeedback,
                    'total' => $formattedFeedback->count()
                ])
            );

        } catch (\Exception $e) {
            Log::error('Get feedback list error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve feedback list: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get freelancer profiles with ratings for the company
     * GET /api/v1/company/GetFreelancerProfiles
     *
     * @return JsonResponse
     */
    public function getFreelancerProfiles(): JsonResponse
    {
        try {
            $user = Auth::user();
            $company = CompanyDetail::where('user_id', $user->user_id)->first();

            if (!$company) {
                return response()->json(
                    MessageHelper::error('Company profile not found'),
                    404
                );
            }

            // Get freelancers who have worked with this company
            $freelancerProfiles = DB::table('contracts as c')
                ->join('users as u', 'c.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('projects as p', 'c.project_id', '=', 'p.project_id')
                ->leftJoin('feedback as f', function($join) use ($user) {
                    $join->on('c.freelancer_id', '=', 'f.freelancer_id')
                         ->where('f.company_id', '=', $user->user_id);
                })
                ->select(
                    'u.user_id as freelancer_id',
                    'ud.first_name',
                    'ud.last_name',
                    'ud.bio',
                    'p.project_title',
                    'p.project_type',
                    'c.start_date',
                    'p.duration_weeks',
                    'c.total_amount',
                    DB::raw('MAX(c.created_at) as latest_contract_date'),
                    DB::raw('COUNT(DISTINCT f.feedback_id) as review_count'),
                    DB::raw('ROUND(AVG(
                        (COALESCE(f.attendance_rating, 0) +
                         COALESCE(f.work_quality_rating, 0) +
                         COALESCE(f.execution_speed_rating, 0) +
                         COALESCE(f.adaptability_rating, 0) +
                         COALESCE(f.general_feedback_rating, 0)) / 5
                    ), 1) as average_rating')
                )
                ->where('c.company_id', $company->company_id)
                ->whereIn('c.status', ['Completed'])
                ->groupBy(
                    'u.user_id',
                    'ud.first_name',
                    'ud.last_name',
                    'ud.bio',
                    'p.project_title',
                    'p.project_type',
                    'c.start_date',
                    'p.duration_weeks',
                    'c.total_amount'
                )
                ->orderBy('latest_contract_date', 'desc')
                ->get();

            $formattedProfiles = $freelancerProfiles->map(function ($profile) {
                return [
                    'id' => $profile->freelancer_id,
                    'freelancerName' => trim($profile->first_name . ' ' . $profile->last_name),
                    'projectName' => $profile->project_title,
                    'date' => $profile->start_date ? \Carbon\Carbon::parse($profile->start_date)->format('d/m/Y') : 'N/A',
                    'duration' => $profile->duration_weeks ? $profile->duration_weeks . ' Weeks' : 'N/A',
                    'spent' => $profile->total_amount ? number_format($profile->total_amount, 2) . ' $' : '0.00 $',
                    'expertise' => $profile->project_type ?? 'General',
                    'rating' => $profile->average_rating ? (int)round($profile->average_rating) : 0,
                    'numberOfReviews' => $profile->review_count ?? 0,
                    'profileSummary' => $profile->bio ?? 'No profile summary available',
                ];
            });

            return response()->json(
                MessageHelper::success('Freelancer profiles retrieved successfully', [
                    'profiles' => $formattedProfiles,
                    'total' => $formattedProfiles->count()
                ])
            );

        } catch (\Exception $e) {
            Log::error('Get freelancer profiles error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve freelancer profiles: ' . $e->getMessage()),
                500
            );
        }
    }
}
