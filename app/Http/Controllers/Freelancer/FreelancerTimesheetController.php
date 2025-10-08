<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class FreelancerTimesheetController extends Controller
{
    /**
     * Get freelancer's own timesheets
     * GET /api/v1/freelancer/timesheets
     */
    public function index(Request $request)
    {
        try {
            $freelancerId = Auth::id();
            
            $query = DB::table('timesheets as t')
                ->join('contracts as c', 't.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.freelancer_id', $freelancerId)
                ->select(
                    't.*',
                    'p.project_title',
                    'cd.company_name',
                    'ts.status_name',
                    'c.hourly_rate'
                )
                ->orderBy('t.created_at', 'desc');

            // Filter by status
            if ($request->has('status')) {
                $query->where('ts.status_name', $request->status);
            }

            $timesheets = $query->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Timesheets retrieved successfully',
                'data' => $timesheets
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve timesheets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dropdown data for creating timesheet
     * GET /api/v1/freelancer/timesheets/dropdown-data
     */
    public function getDropdownData()
    {
        try {
            $freelancerId = Auth::id();

            // Get active contracts for this freelancer
            $contracts = DB::table('contracts as c')
                ->join('projects as p', 'c.project_id', '=', 'p.project_id')
                ->join('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                ->where('c.freelancer_id', $freelancerId)
                ->where('c.status', 'Active')
                ->select(
                    'c.contract_id',
                    'c.project_id',
                    'c.company_id',
                    'p.project_title',
                    'cd.company_name',
                    'c.hourly_rate'
                )
                ->get();

            // Get freelancer hourly rate
            $freelancerRate = DB::table('user_details')
                ->where('user_id', $freelancerId)
                ->value('hourly_rate');

            return response()->json([
                'success' => true,
                'message' => 'Dropdown data retrieved successfully',
                'data' => [
                    'contracts' => $contracts,
                    'freelancer_hourly_rate' => $freelancerRate
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dropdown data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create and submit a new timesheet
     * POST /api/v1/freelancer/timesheets
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required|integer|exists:contracts,contract_id',
            'company_id' => 'required|integer|exists:company_details,company_id',
            'project_id' => 'required|integer|exists:projects,project_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array|size:7',
            'days.*.work_date' => 'required|date',
            'days.*.day_name' => 'required|string',
            'days.*.hours_worked' => 'required|numeric|min:0|max:24',
            'days.*.task_description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $freelancerId = Auth::id();

            // Verify contract belongs to freelancer
            $contract = DB::table('contracts')
                ->where('contract_id', $request->contract_id)
                ->where('freelancer_id', $freelancerId)
                ->first();

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract not found or does not belong to you'
                ], 403);
            }

            // Calculate total hours
            $totalHours = array_sum(array_column($request->days, 'hours_worked'));
            $hourlyRate = $contract->hourly_rate;
            $totalAmount = $totalHours * $hourlyRate;

            // Get pending status
            $pendingStatus = DB::table('timesheet_status')
                ->where('status_name', 'Pending')
                ->first();

            // Create main timesheet record
            $timesheetId = DB::table('timesheets')->insertGetId([
                'contract_id' => $request->contract_id,
                'freelancer_id' => $freelancerId,
                'company_id' => $request->company_id,
                'project_id' => $request->project_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status_id' => $pendingStatus->status_id,
                'status_display_name' => $pendingStatus->status_name,
                'total_hours' => $totalHours,
                'hourly_rate' => $hourlyRate,
                'total_amount' => $totalAmount,
                'submitted_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Insert 7 days data
            foreach ($request->days as $index => $day) {
                DB::table('timesheet_days')->insert([
                    'timesheet_id' => $timesheetId,
                    'work_date' => $day['work_date'],
                    'day_name' => $day['day_name'],
                    'day_number' => $index + 1,
                    'hours_worked' => $day['hours_worked'],
                    'task_description' => $day['task_description'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Create notification for company
            $companyUser = DB::table('company_details')
                ->where('company_id', $request->company_id)
                ->value('user_id');

            DB::table('notifications')->insert([
                'user_id' => $companyUser,
                'title' => 'New Timesheet Submitted',
                'message' => 'A freelancer has submitted a new timesheet for review',
                'type' => 'Timesheet',
                'action_url' => "/company/timesheets/{$timesheetId}",
                'is_read' => false,
                'created_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $freelancerId,
                'action' => 'Timesheet Created',
                'entity_type' => 'timesheet',
                'entity_id' => $timesheetId,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            // Get created timesheet with details
            $timesheet = DB::table('timesheets as t')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->where('t.timesheet_id', $timesheetId)
                ->select('t.*', 'p.project_title', 'cd.company_name')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet created and submitted successfully',
                'data' => $timesheet
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific timesheet details with all days and comments
     * GET /api/v1/freelancer/timesheets/{id}
     */
    public function show($id)
    {
        try {
            $freelancerId = Auth::id();

            // Get timesheet with all details
            $timesheet = DB::table('timesheets as t')
                ->join('contracts as c', 't.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.timesheet_id', $id)
                ->where('t.freelancer_id', $freelancerId)
                ->select(
                    't.*',
                    'p.project_title',
                    'cd.company_name',
                    'ts.status_name',
                    'c.hourly_rate'
                )
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            // Get 7 days data
            $days = DB::table('timesheet_days')
                ->where('timesheet_id', $id)
                ->orderBy('day_number')
                ->get();

            // Get comments for each day
            foreach ($days as $day) {
                $day->comments = DB::table('timesheet_day_comments as tdc')
                    ->leftJoin('users as u', 'tdc.comment_by', '=', 'u.user_id')
                    ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                    ->where('tdc.day_id', $day->day_id)
                    ->select(
                        'tdc.*',
                        DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as commenter_name")
                    )
                    ->get();
            }

            return response()->json([
                'success' => true,
                'message' => 'Timesheet retrieved successfully',
                'data' => [
                    'timesheet' => $timesheet,
                    'days' => $days
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resubmit a rejected timesheet with modifications
     * PUT /api/v1/freelancer/timesheets/{id}/resubmit
     */
    public function resubmit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required|array',
            'days.*.day_id' => 'required|integer|exists:timesheet_days,day_id',
            'days.*.hours_worked' => 'required|numeric|min:0|max:24',
            'days.*.task_description' => 'nullable|string|max:1000',
            'days.*.freelancer_comment' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $freelancerId = Auth::id();

            // Verify timesheet belongs to freelancer and is rejected
            $timesheet = DB::table('timesheets as t')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.timesheet_id', $id)
                ->where('t.freelancer_id', $freelancerId)
                ->select('t.*', 'ts.status_name')
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            if ($timesheet->status_name !== 'Rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only rejected timesheets can be resubmitted'
                ], 400);
            }

            // Update each day's data
            $totalHours = 0;
            foreach ($request->days as $day) {
                DB::table('timesheet_days')
                    ->where('day_id', $day['day_id'])
                    ->where('timesheet_id', $id)
                    ->update([
                        'hours_worked' => $day['hours_worked'],
                        'task_description' => $day['task_description'] ?? null,
                        'updated_at' => now()
                    ]);

                $totalHours += $day['hours_worked'];

                // Add freelancer comment if provided
                if (!empty($day['freelancer_comment'])) {
                    DB::table('timesheet_day_comments')->insert([
                        'day_id' => $day['day_id'],
                        'timesheet_id' => $id,
                        'comment_by' => $freelancerId,
                        'comment_type' => 'Freelancer',
                        'comment_text' => $day['freelancer_comment'],
                        'created_at' => now()
                    ]);
                }
            }

            // Update timesheet status to pending
            $pendingStatus = DB::table('timesheet_status')
                ->where('status_name', 'Pending')
                ->first();

            $totalAmount = $totalHours * $timesheet->hourly_rate;

            DB::table('timesheets')
                ->where('timesheet_id', $id)
                ->update([
                    'status_id' => $pendingStatus->status_id,
                    'status_display_name' => $pendingStatus->status_name,
                    'total_hours' => $totalHours,
                    'total_amount' => $totalAmount,
                    'resubmission_count' => DB::raw('resubmission_count + 1'),
                    'last_resubmitted_at' => now(),
                    'reviewed_at' => null,
                    'reviewed_by' => null,
                    'updated_at' => now()
                ]);

            // Notify company
            $companyUser = DB::table('company_details')
                ->where('company_id', $timesheet->company_id)
                ->value('user_id');

            DB::table('notifications')->insert([
                'user_id' => $companyUser,
                'title' => 'Timesheet Resubmitted',
                'message' => 'A freelancer has resubmitted a timesheet for review',
                'type' => 'Timesheet',
                'action_url' => "/company/timesheets/{$id}",
                'is_read' => false,
                'created_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $freelancerId,
                'action' => 'Timesheet Resubmitted',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet resubmitted successfully'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to resubmit timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Request payment for accepted timesheet
     * POST /api/v1/freelancer/timesheets/{id}/request-payment
     */
    public function requestPayment($id)
    {
        DB::beginTransaction();
        try {
            $freelancerId = Auth::id();

            // Verify timesheet is accepted and belongs to freelancer
            $timesheet = DB::table('timesheets as t')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.timesheet_id', $id)
                ->where('t.freelancer_id', $freelancerId)
                ->select('t.*', 'ts.status_name')
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            if ($timesheet->status_name !== 'Accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only accepted timesheets can request payment'
                ], 400);
            }

            if ($timesheet->payment_requested_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment already requested for this timesheet'
                ], 400);
            }

            // Update timesheet
            DB::table('timesheets')
                ->where('timesheet_id', $id)
                ->update([
                    'payment_requested_at' => now(),
                    'updated_at' => now()
                ]);

            // Create payment request
            $invoice = DB::table('invoices')
                ->where('timesheet_id', $id)
                ->first();

            if ($invoice) {
                DB::table('payment_requests')->insert([
                    'timesheet_id' => $id,
                    'invoice_id' => $invoice->invoice_id,
                    'freelancer_id' => $freelancerId,
                    'requested_amount' => $timesheet->total_amount,
                    'status' => 'Pending',
                    'requested_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Notify admin
            $adminUsers = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('roles.role_name', 'Admin')
                ->pluck('users.user_id');

            foreach ($adminUsers as $adminId) {
                DB::table('notifications')->insert([
                    'user_id' => $adminId,
                    'title' => 'Payment Request',
                    'message' => 'A freelancer has requested payment for timesheet',
                    'type' => 'Payment',
                    'action_url' => "/admin/payment-requests",
                    'is_read' => false,
                    'created_at' => now()
                ]);
            }

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $freelancerId,
                'action' => 'Payment Requested',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment request submitted successfully'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to request payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment history for freelancer
     * GET /api/v1/freelancer/timesheets/payment-history
     */
    public function paymentHistory()
    {
        try {
            $freelancerId = Auth::id();

            $payments = DB::table('payments as p')
                ->join('timesheets as t', 'p.timesheet_id', '=', 't.timesheet_id')
                ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                ->join('projects as proj', 't.project_id', '=', 'proj.project_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->where('t.freelancer_id', $freelancerId)
                ->select(
                    'p.*',
                    't.start_date',
                    't.end_date',
                    't.total_hours',
                    'i.invoice_number',
                    'proj.project_title',
                    'cd.company_name'
                )
                ->orderBy('p.created_at', 'desc')
                ->paginate(15);

            // Get total earnings
            $totalEarnings = DB::table('freelancer_earnings')
                ->where('freelancer_id', $freelancerId)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Payment history retrieved successfully',
                'data' => [
                    'payments' => $payments,
                    'total_earnings' => $totalEarnings
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
