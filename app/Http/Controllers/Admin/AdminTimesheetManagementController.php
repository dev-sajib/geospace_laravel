<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdminTimesheetManagementController extends Controller {
    /**
     * Get all timesheets (admin view)
     * GET /api/v1/admin/timesheets
     */
    public function index( Request $request ) {
        try {
            $query = DB::table( 'timesheets as t' )
                       ->join( 'contracts as c', 't.contract_id', '=', 'c.contract_id' )
                       ->join( 'projects as p', 't.project_id', '=', 'p.project_id' )
                       ->join( 'users as u', 't.freelancer_id', '=', 'u.user_id' )
                       ->join( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                       ->join( 'company_details as cd', 't.company_id', '=', 'cd.company_id' )
                       ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                       ->select(
                           't.*',
                           'c.contract_title',
                           'c.hourly_rate as contract_hourly_rate',
                           'p.project_title',
                           'p.project_type',
                           'cd.company_name',
                           'u.email as freelancer_email',
                           DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                           'ud.profile_image as freelancer_image',
                           'ts.status_name',
                           'ts.status_description',
                           DB::raw( "(t.total_hours * c.hourly_rate) as calculated_amount" )
                       )
                       ->orderBy( 't.created_at', 'desc' );

            // Filters
            if ( $request->has( 'status' ) ) {
                $query->where( 'ts.status_name', $request->status );
            }

            if ( $request->has( 'freelancer_id' ) ) {
                $query->where( 't.freelancer_id', $request->freelancer_id );
            }

            if ( $request->has( 'company_id' ) ) {
                $query->where( 't.company_id', $request->company_id );
            }

            if ( $request->has( 'start_date' ) ) {
                $query->where( 't.start_date', '>=', $request->start_date );
            }

            if ( $request->has( 'end_date' ) ) {
                $query->where( 't.end_date', '<=', $request->end_date );
            }

            $timesheets = $query->paginate( 20 );

            return response()->json( [
                'success' => true,
                'message' => 'Timesheets retrieved successfully',
                'data'    => $timesheets
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve timesheets',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get pending timesheets
     * GET /api/v1/admin/timesheets/pending
     */
    public function pendingTimesheets( Request $request ) {
        try {
            $perPage   = $request->input( 'per_page', 15 );
            $companyId = $request->input( 'company_id' );

            $query = DB::table( 'timesheets as t' )
                       ->leftJoin( 'contracts as c', 't.contract_id', '=', 'c.contract_id' )
                       ->leftJoin( 'projects as p', 'c.project_id', '=', 'p.project_id' )
                       ->leftJoin( 'users as u', 't.freelancer_id', '=', 'u.user_id' )
                       ->leftJoin( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                       ->leftJoin( 'company_details as cd', 't.company_id', '=', 'cd.company_id' )
                       ->select(
                           't.*',
                           'c.contract_title',
                           'c.hourly_rate',
                           'p.project_title',
                           'cd.company_name',
                           'u.email as freelancer_email',
                           DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                           'ud.profile_image as freelancer_image',
                           DB::raw( "(t.total_hours * c.hourly_rate) as calculated_amount" )
                       )
                       ->where( 't.status_id', 1 ); // Pending status

            if ( $companyId ) {
                $query->where( 'c.company_id', $companyId );
            }

            $pendingTimesheets = $query->orderBy( 't.submitted_at', 'asc' )->paginate( $perPage );

            return response()->json( [
                'success' => true,
                'message' => 'Pending timesheets retrieved successfully',
                'data'    => $pendingTimesheets
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve pending timesheets',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get approved timesheets
     * GET /api/v1/admin/timesheets/approved
     */
    public function approvedTimesheets( Request $request ) {
        try {
            $perPage   = $request->input( 'per_page', 15 );
            $companyId = $request->input( 'company_id' );

            $query = DB::table( 'timesheets as t' )
                       ->leftJoin( 'contracts as c', 't.contract_id', '=', 'c.contract_id' )
                       ->leftJoin( 'projects as p', 'c.project_id', '=', 'p.project_id' )
                       ->leftJoin( 'users as u', 't.freelancer_id', '=', 'u.user_id' )
                       ->leftJoin( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                       ->leftJoin( 'company_details as cd', 't.company_id', '=', 'cd.company_id' )
                       ->select(
                           't.*',
                           'c.contract_title',
                           'c.hourly_rate',
                           'p.project_title',
                           'cd.company_name',
                           'u.email as freelancer_email',
                           DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                           'ud.profile_image as freelancer_image',
                           DB::raw( "(t.total_hours * c.hourly_rate) as calculated_amount" )
                       )
                       ->where( 't.status_id', 2 ); // Approved status

            if ( $companyId ) {
                $query->where( 'c.company_id', $companyId );
            }

            $approvedTimesheets = $query->orderBy( 't.reviewed_by', 'desc' )->paginate( $perPage );

            return response()->json( [
                'success' => true,
                'message' => 'Approved timesheets retrieved successfully',
                'data'    => $approvedTimesheets
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve approved timesheets',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get all accepted timesheets
     * GET /api/v1/admin/timesheets/accepted
     */
    public function acceptedTimesheets() {
        try {
            $timesheets = DB::table( 'timesheets as t' )
                            ->join( 'projects as p', 't.project_id', '=', 'p.project_id' )
                            ->join( 'users as u', 't.freelancer_id', '=', 'u.user_id' )
                            ->join( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                            ->join( 'company_details as cd', 't.company_id', '=', 'cd.company_id' )
                            ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                            ->leftJoin( 'invoices as i', 't.timesheet_id', '=', 'i.timesheet_id' )
                            ->where( 'ts.status_name', 'Accepted' )
                            ->select(
                                't.*',
                                'p.project_title',
                                DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                                'u.email as freelancer_email',
                                'cd.company_name',
                                'ts.status_name',
                                'i.invoice_number',
                                'i.invoice_id',
                                'i.status as invoice_status'
                            )
                            ->orderBy( 't.created_at', 'desc' )
                            ->paginate( 20 );

            return response()->json( [
                'success' => true,
                'message' => 'Accepted timesheets retrieved successfully',
                'data'    => $timesheets
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve accepted timesheets',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Display the specified timesheet with all details
     * GET /api/v1/admin/timesheets/{id}
     */
    public function show( $id ) {
        try {
            $timesheet = DB::table( 'timesheets as t' )
                           ->leftJoin( 'contracts as c', 't.contract_id', '=', 'c.contract_id' )
                           ->leftJoin( 'projects as p', 'c.project_id', '=', 'p.project_id' )
                           ->leftJoin( 'users as u', 't.freelancer_id', '=', 'u.user_id' )
                           ->leftJoin( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                           ->leftJoin( 'company_details as cd', 't.company_id', '=', 'cd.company_id' )
                           ->leftJoin( 'users as cu', 'cd.user_id', '=', 'cu.user_id' )
                           ->leftJoin( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                           ->leftJoin( 'users as approver', 't.reviewed_by', '=', 'approver.user_id' )
                           ->leftJoin( 'user_details as approver_details', 'approver.user_id', '=', 'approver_details.user_id' )
                           ->where( 't.timesheet_id', $id )
                           ->select(
                               't.*',
                               'c.contract_title',
                               'c.contract_description',
                               'c.hourly_rate as contract_hourly_rate',
                               'c.status as contract_status',
                               'c.start_date as contract_start_date',
                               'c.end_date as contract_end_date',
                               'p.project_title',
                               'p.project_description',
                               'p.project_type',
                               'cd.company_name',
                               'cd.company_type',
                               'cd.logo as company_logo',
                               'cu.email as company_email',
                               'u.email as freelancer_email',
                               DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                               'ud.profile_image as freelancer_image',
                               'ud.phone as freelancer_phone',
                               'ud.hourly_rate as freelancer_hourly_rate',
                               'ts.status_name',
                               'ts.status_description',
                               DB::raw( "CONCAT(approver_details.first_name, ' ', approver_details.last_name) as approved_by_name" ),
                               'approver.email as approver_email',
                               DB::raw( "(t.total_hours * c.hourly_rate) as calculated_amount" )
                           )
                           ->first();

            if ( ! $timesheet ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404 );
            }

            return response()->json( [
                'success' => true,
                'message' => 'Timesheet retrieved successfully',
                'data'    => $timesheet
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve timesheet',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get detailed timesheet information with days and comments
     * GET /api/v1/admin/timesheets/{id}/details
     */
    public function getTimesheetDetails( $id ) {
        try {
            // Get main timesheet with related info
            // In getTimesheetDetails method, update the query:

            $timesheet = DB::table( 'timesheets as t' )
                           ->leftJoin( 'contracts as c', 't.contract_id', '=', 'c.contract_id' )
                           ->leftJoin( 'projects as p', 't.project_id', '=', 'p.project_id' )
                           ->leftJoin( 'users as u', 't.freelancer_id', '=', 'u.user_id' )
                           ->leftJoin( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                           ->leftJoin( 'company_details as cd', 't.company_id', '=', 'cd.company_id' )
                           ->leftJoin( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                           ->leftJoin( 'users as reviewer', 't.reviewed_by', '=', 'reviewer.user_id' )
                           ->leftJoin( 'user_details as reviewer_details', 'reviewer.user_id', '=', 'reviewer_details.user_id' )
                           ->where( 't.timesheet_id', $id )
                           ->select(
                               't.timesheet_id',
                               't.contract_id',
                               't.freelancer_id',
                               't.company_id',
                               't.project_id',
                               't.start_date',
                               't.end_date',
                               't.total_hours',
                               't.hourly_rate',
                               't.total_amount',
                               't.status_id',
                               't.submitted_at',
                               't.reviewed_at',
                               't.reviewed_by',
                               'p.project_title',
                               'p.project_description',
                               DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                               'u.email as freelancer_email',
                               'ud.phone as freelancer_phone',
                               'cd.company_name',
                               'cd.company_type',
                               'ts.status_name',
                               'c.hourly_rate as contract_hourly_rate',
                               'c.contract_title',
                               DB::raw( "CONCAT(reviewer_details.first_name, ' ', reviewer_details.last_name) as approved_by_name" )
                           )
                           ->first();

            if ( ! $timesheet ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404 );
            }

            // Get timesheet days
            $days = DB::table( 'timesheet_days as td' )
                      ->where( 'td.timesheet_id', $id )
                      ->orderBy( 'td.day_number', 'asc' )
                      ->get();

            // Get comments for each day
            foreach ( $days as $day ) {
                $day->comments = DB::table( 'timesheet_day_comments as tdc' )
                                   ->leftJoin( 'users as u', 'tdc.comment_by', '=', 'u.user_id' )
                                   ->leftJoin( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                                   ->where( 'tdc.day_id', $day->day_id )
                                   ->select(
                                       'tdc.*',
                                       DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as commenter_name" )
                                   )
                                   ->orderBy( 'tdc.created_at', 'desc' )
                                   ->get();
            }

            // Get invoice if exists
            $invoice = DB::table( 'invoices' )
                         ->where( 'timesheet_id', $id )
                         ->first();

            // Get payment requests
            $paymentRequests = DB::table( 'payment_requests' )
                                 ->where( 'timesheet_id', $id )
                                 ->orderBy( 'created_at', 'desc' )
                                 ->get();

            // Get payments
            $payments = DB::table( 'payments' )
                          ->where( 'timesheet_id', $id )
                          ->orderBy( 'created_at', 'desc' )
                          ->get();

            return response()->json( [
                'success' => true,
                'message' => 'Timesheet retrieved successfully',
                'data'    => [
                    'timesheet'        => $timesheet,
                    'days'             => $days,
                    'invoice'          => $invoice,
                    'payment_requests' => $paymentRequests,
                    'payments'         => $payments
                ]
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve timesheet',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Create a new timesheet
     * POST /api/v1/admin/timesheets
     */
    public function store( Request $request ) {
        $validator = Validator::make( $request->all(), [
            'contract_id'      => 'required|integer|exists:contracts,contract_id',
            'user_id'          => 'required|integer|exists:users,user_id',
            'work_date'        => 'required|date',
            'work_hours'       => 'required|numeric|min:0.25|max:24',
            'task_description' => 'nullable|string|max:1000'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422 );
        }

        try {
            // Get contract details
            $contract = DB::table( 'contracts' )
                          ->where( 'contract_id', $request->contract_id )
                          ->first();

            if ( ! $contract ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Contract not found'
                ], 404 );
            }

            // Get pending status
            $pendingStatus = DB::table( 'timesheet_status' )
                               ->where( 'status_name', 'Pending' )
                               ->first();

            // Calculate amount
            $amount = $request->work_hours * $contract->hourly_rate;

            $timesheetId = DB::table( 'timesheets' )->insertGetId( [
                'contract_id'         => $request->contract_id,
                'user_id'             => $request->user_id,
                'work_date'           => $request->work_date,
                'day_of_week'         => date( 'l', strtotime( $request->work_date ) ),
                'work_hours'          => $request->work_hours,
                'hourly_rate'         => $contract->hourly_rate,
                'total_amount'        => $amount,
                'task_description'    => $request->task_description,
                'status_id'           => $pendingStatus->status_id ?? 1,
                'status_display_name' => $pendingStatus->status_name ?? 'Pending',
                'submitted_at'        => now(),
                'created_at'          => now(),
                'updated_at'          => now()
            ] );

            // Log activity
            DB::table( 'activity_logs' )->insert( [
                'user_id'     => auth()->id() ?? $request->user_id,
                'action'      => 'Timesheet Created',
                'entity_type' => 'timesheet',
                'entity_id'   => $timesheetId,
                'new_values'  => json_encode( $request->all() ),
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'created_at'  => now()
            ] );

            // Create notification for company
            DB::table( 'notifications' )->insert( [
                'user_id'    => $contract->company_id,
                'title'      => 'New Timesheet Submitted',
                'message'    => "A new timesheet has been submitted for review.",
                'type'       => 'Info',
                'action_url' => "/timesheets/{$timesheetId}",
                'created_at' => now()
            ] );

            $timesheet = DB::table( 'timesheets' )->where( 'timesheet_id', $timesheetId )->first();

            return response()->json( [
                'success' => true,
                'message' => 'Timesheet created successfully',
                'data'    => $timesheet
            ], 201 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to create timesheet',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Update the specified timesheet
     * PUT/PATCH /api/v1/admin/timesheets/{id}
     */
    public function update( Request $request, $id ) {
        $validator = Validator::make( $request->all(), [
            'work_date'        => 'nullable|date',
            'work_hours'       => 'nullable|numeric|min:0.25|max:24',
            'task_description' => 'nullable|string|max:1000',
            'status_id'        => 'nullable|integer|exists:timesheet_status,status_id',
            'rejected_reason'  => 'nullable|string|max:500'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422 );
        }

        try {
            $timesheet = DB::table( 'timesheets' )->where( 'timesheet_id', $id )->first();

            if ( ! $timesheet ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404 );
            }

            $updateData = [];

            if ( $request->has( 'work_date' ) ) {
                $updateData['work_date']   = $request->work_date;
                $updateData['day_of_week'] = date( 'l', strtotime( $request->work_date ) );
            }

            if ( $request->has( 'work_hours' ) ) {
                $updateData['work_hours'] = $request->work_hours;
            }

            if ( $request->has( 'task_description' ) ) {
                $updateData['task_description'] = $request->task_description;
            }

            if ( $request->has( 'status_id' ) ) {
                $status                            = DB::table( 'timesheet_status' )->where( 'status_id', $request->status_id )->first();
                $updateData['status_id']           = $request->status_id;
                $updateData['status_display_name'] = $status->status_name ?? null;
            }

            if ( $request->has( 'rejected_reason' ) ) {
                $updateData['rejected_reason'] = $request->rejected_reason;
            }

            $updateData['updated_at'] = now();

            DB::table( 'timesheets' )->where( 'timesheet_id', $id )->update( $updateData );

            // Log activity
            DB::table( 'activity_logs' )->insert( [
                'user_id'     => auth()->id(),
                'action'      => 'Timesheet Updated',
                'entity_type' => 'timesheet',
                'entity_id'   => $id,
                'old_values'  => json_encode( $timesheet ),
                'new_values'  => json_encode( $updateData ),
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'created_at'  => now()
            ] );

            $updatedTimesheet = DB::table( 'timesheets' )->where( 'timesheet_id', $id )->first();

            return response()->json( [
                'success' => true,
                'message' => 'Timesheet updated successfully',
                'data'    => $updatedTimesheet
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to update timesheet',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Approve a timesheet
     * POST /api/v1/admin/timesheets/{id}/approve
     */
    public function approve( Request $request, $id ) {
        try {
            $timesheet = DB::table( 'timesheets' )->where( 'timesheet_id', $id )->first();

            if ( ! $timesheet ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404 );
            }

            if ( $timesheet->status_id == 2 ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Timesheet is already approved'
                ], 400 );
            }

            // Get approved status (assuming status_id 2 is Approved)
            $approvedStatus = DB::table( 'timesheet_status' )->where( 'status_id', 2 )->first();

            DB::table( 'timesheets' )->where( 'timesheet_id', $id )->update( [
                'status_id'           => 2,
                'status_display_name' => $approvedStatus->status_name ?? 'Approved',
                'reviewed_by'         => auth()->id(),
                'approved_at'         => now(),
                'updated_at'          => now()
            ] );

            // Log activity
            DB::table( 'activity_logs' )->insert( [
                'user_id'     => auth()->id(),
                'action'      => 'Timesheet Approved',
                'entity_type' => 'timesheet',
                'entity_id'   => $id,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'created_at'  => now()
            ] );

            // Notify freelancer
            DB::table( 'notifications' )->insert( [
                'user_id'    => $timesheet->user_id,
                'title'      => 'Timesheet Approved',
                'message'    => "Your timesheet has been approved.",
                'type'       => 'Success',
                'action_url' => "/timesheets/{$id}",
                'created_at' => now()
            ] );

            $updatedTimesheet = DB::table( 'timesheets' )->where( 'timesheet_id', $id )->first();

            return response()->json( [
                'success' => true,
                'message' => 'Timesheet approved successfully',
                'data'    => $updatedTimesheet
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to approve timesheet',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Reject a timesheet
     * POST /api/v1/admin/timesheets/{id}/reject
     */
    public function reject( Request $request, $id ) {
        $validator = Validator::make( $request->all(), [
            'rejected_reason' => 'required|string|max:500'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422 );
        }

        try {
            $timesheet = DB::table( 'timesheets' )->where( 'timesheet_id', $id )->first();

            if ( ! $timesheet ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404 );
            }

            // Get rejected status (assuming status_id 3 is Rejected)
            $rejectedStatus = DB::table( 'timesheet_status' )->where( 'status_id', 3 )->first();

            DB::table( 'timesheets' )->where( 'timesheet_id', $id )->update( [
                'status_id'           => 3,
                'status_display_name' => $rejectedStatus->status_name ?? 'Rejected',
                'rejected_reason'     => $request->rejected_reason,
                'reviewed_by'         => auth()->id(),
                'approved_at'         => now(),
                'updated_at'          => now()
            ] );

            // Log activity
            DB::table( 'activity_logs' )->insert( [
                'user_id'     => auth()->id(),
                'action'      => 'Timesheet Rejected',
                'entity_type' => 'timesheet',
                'entity_id'   => $id,
                'new_values'  => json_encode( [ 'rejected_reason' => $request->rejected_reason ] ),
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'created_at'  => now()
            ] );

            // Notify freelancer
            DB::table( 'notifications' )->insert( [
                'user_id'    => $timesheet->user_id,
                'title'      => 'Timesheet Rejected',
                'message'    => "Your timesheet has been rejected. Reason: {$request->rejected_reason}",
                'type'       => 'Warning',
                'action_url' => "/timesheets/{$id}",
                'created_at' => now()
            ] );

            $updatedTimesheet = DB::table( 'timesheets' )->where( 'timesheet_id', $id )->first();

            return response()->json( [
                'success' => true,
                'message' => 'Timesheet rejected successfully',
                'data'    => $updatedTimesheet
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to reject timesheet',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Delete timesheet (soft delete or hard delete based on status)
     * DELETE /api/v1/admin/timesheets/{id}
     */
    public function destroy( $id ) {
        DB::beginTransaction();
        try {
            $timesheet = DB::table( 'timesheets as t' )
                           ->join( 'timesheet_status as ts', 't.status_id', '=', 'ts.status_id' )
                           ->where( 't.timesheet_id', $id )
                           ->select( 't.*', 'ts.status_name' )
                           ->first();

            if ( ! $timesheet ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404 );
            }

            // Check if has payments
            $hasPayments = DB::table( 'payments' )
                             ->where( 'timesheet_id', $id )
                             ->exists();

            if ( $hasPayments ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Cannot delete timesheet with existing payments'
                ], 400 );
            }

            // Delete related records
            DB::table( 'timesheet_day_comments' )
              ->where( 'timesheet_id', $id )
              ->delete();

            DB::table( 'timesheet_days' )
              ->where( 'timesheet_id', $id )
              ->delete();

            DB::table( 'timesheets' )
              ->where( 'timesheet_id', $id )
              ->delete();

            // Log activity
            DB::table( 'activity_logs' )->insert( [
                'user_id'     => Auth::id(),
                'action'      => 'Timesheet Deleted',
                'entity_type' => 'timesheet',
                'entity_id'   => $id,
                'old_values'  => json_encode( $timesheet ),
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
                'created_at'  => now()
            ] );

            DB::commit();

            return response()->json( [
                'success' => true,
                'message' => 'Timesheet deleted successfully'
            ], 200 );

        } catch ( Exception $e ) {
            DB::rollBack();

            return response()->json( [
                'success' => false,
                'message' => 'Failed to delete timesheet',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get timesheet statistics
     * GET /api/v1/admin/timesheets/stats
     */
    public function statistics( Request $request ) {
        try {
            $contractId   = $request->input( 'contract_id' );
            $freelancerId = $request->input( 'freelancer_id' );
            $companyId    = $request->input( 'company_id' );
            $startDate    = $request->input( 'start_date' );
            $endDate      = $request->input( 'end_date' );

            $query = DB::table( 'timesheets as t' )
                       ->leftJoin( 'contracts as c', 't.contract_id', '=', 'c.contract_id' );

            if ( $contractId ) {
                $query->where( 't.contract_id', $contractId );
            }
            if ( $freelancerId ) {
                $query->where( 't.freelancer_id', $freelancerId );
            }
            if ( $companyId ) {
                $query->where( 't.company_id', $companyId );
            }
            if ( $startDate ) {
                $query->where( 't.start_date', '>=', $startDate );
            }
            if ( $endDate ) {
                $query->where( 't.end_date', '<=', $endDate );
            }

            $stats = [
                'total_timesheets'         => ( clone $query )->count(),
                'pending_timesheets'       => ( clone $query )->where( 't.status_id', 1 )->count(),
                'approved_timesheets'      => ( clone $query )->where( 't.status_id', 2 )->count(),
                'rejected_timesheets'      => ( clone $query )->where( 't.status_id', 3 )->count(),
                'accepted_timesheets'      => ( clone $query )->where( 't.status_id', 4 )->count(),
                'total_hours'              => ( clone $query )->sum( 't.total_hours' ),
                'total_amount'             => ( clone $query )->sum( 't.total_amount' ),
                'pending_payment_requests' => DB::table( 'payment_requests' )
                                                ->where( 'status', 'Pending' )
                                                ->count(),
                'pending_company_payments' => DB::table( 'payments' )
                                                ->where( 'status', 'Pending' )
                                                ->count()
            ];

            return response()->json( [
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data'    => $stats
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get all payment requests from freelancers
     * GET /api/v1/admin/payment-requests
     */
    public function paymentRequests( Request $request ) {
        try {
            $query = DB::table( 'payment_requests as pr' )
                       ->join( 'timesheets as t', 'pr.timesheet_id', '=', 't.timesheet_id' )
                       ->join( 'invoices as i', 'pr.invoice_id', '=', 'i.invoice_id' )
                       ->join( 'projects as p', 't.project_id', '=', 'p.project_id' )
                       ->join( 'users as u', 'pr.freelancer_id', '=', 'u.user_id' )
                       ->join( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                       ->join( 'company_details as cd', 't.company_id', '=', 'cd.company_id' )
                       ->select(
                           'pr.*',
                           't.start_date',
                           't.end_date',
                           't.total_hours',
                           'i.invoice_number',
                           'p.project_title',
                           DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                           'u.email as freelancer_email',
                           'cd.company_name'
                       )
                       ->orderBy( 'pr.requested_at', 'desc' );

            // Filter by status
            if ( $request->has( 'status' ) ) {
                $query->where( 'pr.status', $request->status );
            }

            $paymentRequests = $query->paginate( 20 );

            return response()->json( [
                'success' => true,
                'message' => 'Payment requests retrieved successfully',
                'data'    => $paymentRequests
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve payment requests',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Process freelancer payment
     * POST /api/v1/admin/payment-requests/{requestId}/process
     */
    public function processFreelancerPayment( Request $request, $requestId ) {
        $validator = Validator::make( $request->all(), [
            'transaction_id' => 'required|string|max:255',
            'payment_method' => 'required|string|max:100',
            'payment_notes'  => 'nullable|string|max:1000'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422 );
        }

        DB::beginTransaction();
        try {
            $paymentRequest = DB::table( 'payment_requests as pr' )
                                ->join( 'timesheets as t', 'pr.timesheet_id', '=', 't.timesheet_id' )
                                ->where( 'pr.request_id', $requestId )
                                ->select( 'pr.*', 't.freelancer_id', 't.total_amount' )
                                ->first();

            if ( ! $paymentRequest ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Payment request not found'
                ], 404 );
            }

            if ( $paymentRequest->status !== 'Pending' ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Payment request already processed'
                ], 400 );
            }

            // Update payment request status
            DB::table( 'payment_requests' )
              ->where( 'request_id', $requestId )
              ->update( [
                  'status'        => 'Completed',
                  'processed_at'  => now(),
                  'processed_by'  => Auth::id(),
                  'payment_notes' => $request->payment_notes,
                  'updated_at'    => now()
              ] );

            // Create or update payment record
            $payment = DB::table( 'payments' )
                         ->where( 'invoice_id', $paymentRequest->invoice_id )
                         ->where( 'payment_type', 'Freelancer Payment' )
                         ->first();

            if ( $payment ) {
                DB::table( 'payments' )
                  ->where( 'payment_id', $payment->payment_id )
                  ->update( [
                      'status'         => 'Completed',
                      'transaction_id' => $request->transaction_id,
                      'payment_method' => $request->payment_method,
                      'paid_at'        => now(),
                      'updated_at'     => now()
                  ] );
            } else {
                DB::table( 'payments' )->insert( [
                    'invoice_id'     => $paymentRequest->invoice_id,
                    'timesheet_id'   => $paymentRequest->timesheet_id,
                    'amount'         => $paymentRequest->requested_amount,
                    'payment_type'   => 'Freelancer Payment',
                    'status'         => 'Completed',
                    'transaction_id' => $request->transaction_id,
                    'payment_method' => $request->payment_method,
                    'paid_at'        => now(),
                    'created_at'     => now(),
                    'updated_at'     => now()
                ] );
            }

            // Update freelancer earnings
            $earnings = DB::table( 'freelancer_earnings' )
                          ->where( 'freelancer_id', $paymentRequest->freelancer_id )
                          ->first();

            if ( $earnings ) {
                DB::table( 'freelancer_earnings' )
                  ->where( 'freelancer_id', $paymentRequest->freelancer_id )
                  ->update( [
                      'pending_amount' => DB::raw( 'pending_amount - ' . $paymentRequest->requested_amount ),
                      'total_paid'     => DB::raw( 'total_paid + ' . $paymentRequest->requested_amount ),
                      'updated_at'     => now()
                  ] );
            } else {
                DB::table( 'freelancer_earnings' )->insert( [
                    'freelancer_id'  => $paymentRequest->freelancer_id,
                    'total_earned'   => $paymentRequest->requested_amount,
                    'total_paid'     => $paymentRequest->requested_amount,
                    'pending_amount' => 0,
                    'created_at'     => now(),
                    'updated_at'     => now()
                ] );
            }

            // Notify freelancer
            DB::table( 'notifications' )->insert( [
                'user_id'    => $paymentRequest->freelancer_id,
                'title'      => 'Payment Completed',
                'message'    => 'Your payment has been processed successfully',
                'type'       => 'Payment',
                'action_url' => '/freelancer/payments/history',
                'is_read'    => false,
                'created_at' => now()
            ] );

            // Log activity
            DB::table( 'activity_logs' )->insert( [
                'user_id'     => Auth::id(),
                'action'      => 'Freelancer Payment Processed',
                'entity_type' => 'payment_request',
                'entity_id'   => $requestId,
                'new_values'  => json_encode( $request->all() ),
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'created_at'  => now()
            ] );

            DB::commit();

            return response()->json( [
                'success' => true,
                'message' => 'Freelancer payment processed successfully'
            ], 200 );

        } catch ( Exception $e ) {
            DB::rollBack();

            return response()->json( [
                'success' => false,
                'message' => 'Failed to process payment',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Get company payments for verification
     * GET /api/v1/admin/payments/company-payments
     */
    public function companyPayments( Request $request ) {
        try {
            $query = DB::table( 'payments as p' )
                       ->join( 'invoices as i', 'p.invoice_id', '=', 'i.invoice_id' )
                       ->join( 'timesheets as t', 'i.timesheet_id', '=', 't.timesheet_id' )
                       ->join( 'company_details as cd', 'i.company_id', '=', 'cd.company_id' )
                       ->join( 'projects as proj', 't.project_id', '=', 'proj.project_id' )
                       ->select(
                           'p.*',
                           'i.invoice_number',
                           't.timesheet_id',
                           't.start_date',
                           't.end_date',
                           't.total_hours',
                           'cd.company_name',
                           'proj.project_title'
                       )
                       ->where( 'p.status', 'Pending' )
                       ->orderBy( 'p.created_at', 'desc' );

            $payments = $query->paginate( 20 );

            return response()->json( [
                'success' => true,
                'message' => 'Company payments retrieved successfully',
                'data'    => $payments
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve company payments',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Verify and approve company payment
     * POST /api/v1/admin/payments/{paymentId}/verify
     */
    public function verifyCompanyPayment( Request $request, $paymentId ) {
        $validator = Validator::make( $request->all(), [
            'verification_notes' => 'nullable|string|max:1000'
        ] );

        if ( $validator->fails() ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422 );
        }

        DB::beginTransaction();
        try {
            $payment = DB::table( 'payments' )
                         ->where( 'payment_id', $paymentId )
                         ->first();

            if ( ! $payment ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404 );
            }

            // Update payment status to approved
            DB::table( 'payments' )
              ->where( 'payment_id', $paymentId )
              ->update( [
                  'status'     => 'Approved',
                  'updated_at' => now()
              ] );

            // Log activity
            DB::table( 'activity_logs' )->insert( [
                'user_id'     => Auth::id(),
                'action'      => 'Company Payment Verified',
                'entity_type' => 'payment',
                'entity_id'   => $paymentId,
                'new_values'  => json_encode( $request->all() ),
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'created_at'  => now()
            ] );

            DB::commit();

            return response()->json( [
                'success' => true,
                'message' => 'Payment verified and approved successfully'
            ], 200 );

        } catch ( Exception $e ) {
            DB::rollBack();

            return response()->json( [
                'success' => false,
                'message' => 'Failed to verify payment',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }

    /**
     * Download invoice PDF
     * GET /api/v1/admin/invoices/{invoiceId}/download
     */
    public function downloadInvoice( $invoiceId ) {
        try {
            $invoice = DB::table( 'invoices as i' )
                         ->join( 'timesheets as t', 'i.timesheet_id', '=', 't.timesheet_id' )
                         ->join( 'company_details as cd', 'i.company_id', '=', 'cd.company_id' )
                         ->join( 'users as u', 'i.freelancer_id', '=', 'u.user_id' )
                         ->join( 'user_details as ud', 'u.user_id', '=', 'ud.user_id' )
                         ->join( 'projects as p', 't.project_id', '=', 'p.project_id' )
                         ->where( 'i.invoice_id', $invoiceId )
                         ->select(
                             'i.*',
                             't.start_date',
                             't.end_date',
                             't.total_hours',
                             'cd.company_name',
                             'cd.company_address',
                             'cd.company_email',
                             DB::raw( "CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name" ),
                             'u.email as freelancer_email',
                             'ud.address as freelancer_address',
                             'p.project_title'
                         )
                         ->first();

            if ( ! $invoice ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404 );
            }

            // Get timesheet days
            $days = DB::table( 'timesheet_days' )
                      ->where( 'timesheet_id', $invoice->timesheet_id )
                      ->orderBy( 'day_number' )
                      ->get();

            return response()->json( [
                'success' => true,
                'message' => 'Invoice data retrieved successfully',
                'data'    => [
                    'invoice' => $invoice,
                    'days'    => $days
                ]
            ], 200 );

        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve invoice',
                'error'   => $e->getMessage()
            ], 500 );
        }
    }
}
