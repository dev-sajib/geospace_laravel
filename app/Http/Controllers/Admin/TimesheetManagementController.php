<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class TimesheetManagementController extends Controller
{
    /**
     * Display a listing of all timesheets
     * GET /api/v1/admin/timesheets
     */
    public function index()
    {
        try {
            $query = DB::table('timesheets as t')
                       ->leftJoin('contracts as c', 't.contract_id', '=', 'c.contract_id')
                       ->leftJoin('projects as p', 'c.project_id', '=', 'p.project_id')
                       ->leftJoin('users as u', 't.user_id', '=', 'u.user_id')
                       ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                       ->leftJoin('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                       ->leftJoin('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                       ->leftJoin('users as approver', 't.approved_by', '=', 'approver.user_id')
                       ->leftJoin('user_details as approver_details', 'approver.user_id', '=', 'approver_details.user_id')
                       ->select(
                           't.*',
                           'c.contract_title',
                           'c.hourly_rate as contract_hourly_rate',
                           'p.project_title',
                           'p.project_type',
                           'cd.company_name',
                           'u.email as freelancer_email',
                           DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                           'ud.profile_image as freelancer_image',
                           'ts.status_name',
                           'ts.status_description',
                           DB::raw("CONCAT(approver_details.first_name, ' ', approver_details.last_name) as approved_by_name"),
                           DB::raw("(t.work_hours * c.hourly_rate) as calculated_amount")
                       );

            $timesheets = $query->get();

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
     * Display the specified timesheet with all details
     * GET /api/v1/admin/timesheets/{id}
     */
    public function show($id)
    {
        try {
            $timesheet = DB::table('timesheets as t')
                           ->leftJoin('contracts as c', 't.contract_id', '=', 'c.contract_id')
                           ->leftJoin('projects as p', 'c.project_id', '=', 'p.project_id')
                           ->leftJoin('users as u', 't.user_id', '=', 'u.user_id')
                           ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                           ->leftJoin('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                           ->leftJoin('users as cu', 'cd.user_id', '=', 'cu.user_id')
                           ->leftJoin('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                           ->leftJoin('users as approver', 't.approved_by', '=', 'approver.user_id')
                           ->leftJoin('user_details as approver_details', 'approver.user_id', '=', 'approver_details.user_id')
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
                               DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                               'ud.profile_image as freelancer_image',
                               'ud.phone as freelancer_phone',
                               'ud.hourly_rate as freelancer_hourly_rate',
                               'ts.status_name',
                               'ts.status_description',
                               DB::raw("CONCAT(approver_details.first_name, ' ', approver_details.last_name) as approved_by_name"),
                               'approver.email as approver_email',
                               DB::raw("(t.work_hours * c.hourly_rate) as calculated_amount")
                           )
                           ->where('t.timesheet_id', $id)
                           ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            // Get related payments
            $payments = DB::table('payments')
                          ->where('timesheet_id', $id)
                          ->orderBy('created_at', 'desc')
                          ->get();

            // Get timesheet history (if you track revisions)
            $history = DB::table('activity_logs')
                         ->where('entity_type', 'timesheet')
                         ->where('entity_id', $id)
                         ->orderBy('created_at', 'desc')
                         ->get();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet retrieved successfully',
                'data' => [
                    'timesheet' => $timesheet,
                    'payments' => $payments,
                    'history' => $history
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
     * Store a newly created timesheet
     * POST /api/v1/admin/timesheets
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required|integer|exists:contracts,contract_id',
            'user_id' => 'required|integer|exists:users,user_id',
            'work_date' => 'required|date',
            'work_hours' => 'required|numeric|min:0.25|max:24',
            'task_description' => 'required|string|max:1000',
            'status_id' => 'nullable|integer|exists:timesheet_status,status_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verify contract is active
            $contract = DB::table('contracts')->where('contract_id', $request->contract_id)->first();
            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract not found'
                ], 404);
            }

            // Check if user is assigned to this contract
            if ($contract->freelancer_id != $request->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not assigned to this contract'
                ], 403);
            }

            // Get day of week
            $dayOfWeek = date('l', strtotime($request->work_date));

            // Get status display name
            $statusId = $request->status_id ?? 1; // Default to Pending
            $status = DB::table('timesheet_status')->where('status_id', $statusId)->first();

            $timesheetId = DB::table('timesheets')->insertGetId([
                'contract_id' => $request->contract_id,
                'user_id' => $request->user_id,
                'work_date' => $request->work_date,
                'day_of_week' => $dayOfWeek,
                'work_hours' => $request->work_hours,
                'task_description' => $request->task_description,
                'status_id' => $statusId,
                'status_display_name' => $status->status_name ?? 'Pending',
                'submitted_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id() ?? $request->user_id,
                'action' => 'Timesheet Created',
                'entity_type' => 'timesheet',
                'entity_id' => $timesheetId,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            // Create notification for company
            DB::table('notifications')->insert([
                'user_id' => $contract->company_id,
                'title' => 'New Timesheet Submitted',
                'message' => "A new timesheet has been submitted for review.",
                'type' => 'Info',
                'action_url' => "/timesheets/{$timesheetId}",
                'created_at' => now()
            ]);

            $timesheet = DB::table('timesheets')->where('timesheet_id', $timesheetId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet created successfully',
                'data' => $timesheet
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified timesheet
     * PUT/PATCH /api/v1/admin/timesheets/{id}
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'work_date' => 'nullable|date',
            'work_hours' => 'nullable|numeric|min:0.25|max:24',
            'task_description' => 'nullable|string|max:1000',
            'status_id' => 'nullable|integer|exists:timesheet_status,status_id',
            'rejected_reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $timesheet = DB::table('timesheets')->where('timesheet_id', $id)->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            $updateData = [];

            if ($request->has('work_date')) {
                $updateData['work_date'] = $request->work_date;
                $updateData['day_of_week'] = date('l', strtotime($request->work_date));
            }

            if ($request->has('work_hours')) {
                $updateData['work_hours'] = $request->work_hours;
            }

            if ($request->has('task_description')) {
                $updateData['task_description'] = $request->task_description;
            }

            if ($request->has('status_id')) {
                $status = DB::table('timesheet_status')->where('status_id', $request->status_id)->first();
                $updateData['status_id'] = $request->status_id;
                $updateData['status_display_name'] = $status->status_name ?? null;
            }

            if ($request->has('rejected_reason')) {
                $updateData['rejected_reason'] = $request->rejected_reason;
            }

            $updateData['updated_at'] = now();

            DB::table('timesheets')->where('timesheet_id', $id)->update($updateData);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Timesheet Updated',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'old_values' => json_encode($timesheet),
                'new_values' => json_encode($updateData),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            $updatedTimesheet = DB::table('timesheets')->where('timesheet_id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet updated successfully',
                'data' => $updatedTimesheet
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a timesheet
     * POST /api/v1/admin/timesheets/{id}/approve
     */
    public function approve(Request $request, $id)
    {
        try {
            $timesheet = DB::table('timesheets')->where('timesheet_id', $id)->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            if ($timesheet->status_id == 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet is already approved'
                ], 400);
            }

            // Get approved status (assuming status_id 2 is Approved)
            $approvedStatus = DB::table('timesheet_status')->where('status_id', 2)->first();

            DB::table('timesheets')->where('timesheet_id', $id)->update([
                'status_id' => 2,
                'status_display_name' => $approvedStatus->status_name ?? 'Approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'rejected_reason' => null,
                'updated_at' => now()
            ]);

            // Create payment record if needed
            $contract = DB::table('contracts')->where('contract_id', $timesheet->contract_id)->first();
            $amount = $timesheet->work_hours * $contract->hourly_rate;

            DB::table('payments')->insert([
                'contract_id' => $timesheet->contract_id,
                'timesheet_id' => $id,
                'amount' => $amount,
                'currency' => $contract->currency ?? 'CAD',
                'payment_type' => 'Hourly',
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Timesheet Approved',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            // Notify freelancer
            DB::table('notifications')->insert([
                'user_id' => $timesheet->user_id,
                'title' => 'Timesheet Approved',
                'message' => "Your timesheet for {$timesheet->work_date} has been approved.",
                'type' => 'Success',
                'action_url' => "/timesheets/{$id}",
                'created_at' => now()
            ]);

            $updatedTimesheet = DB::table('timesheets')->where('timesheet_id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet approved successfully',
                'data' => $updatedTimesheet
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a timesheet
     * POST /api/v1/admin/timesheets/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejected_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $timesheet = DB::table('timesheets')->where('timesheet_id', $id)->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            // Get rejected status (assuming status_id 3 is Rejected)
            $rejectedStatus = DB::table('timesheet_status')->where('status_id', 3)->first();

            DB::table('timesheets')->where('timesheet_id', $id)->update([
                'status_id' => 3,
                'status_display_name' => $rejectedStatus->status_name ?? 'Rejected',
                'rejected_reason' => $request->rejected_reason,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Timesheet Rejected',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'new_values' => json_encode(['rejected_reason' => $request->rejected_reason]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            // Notify freelancer
            DB::table('notifications')->insert([
                'user_id' => $timesheet->user_id,
                'title' => 'Timesheet Rejected',
                'message' => "Your timesheet for {$timesheet->work_date} has been rejected. Reason: {$request->rejected_reason}",
                'type' => 'Warning',
                'action_url' => "/timesheets/{$id}",
                'created_at' => now()
            ]);

            $updatedTimesheet = DB::table('timesheets')->where('timesheet_id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet rejected successfully',
                'data' => $updatedTimesheet
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified timesheet
     * DELETE /api/v1/admin/timesheets/{id}
     */
    public function destroy($id)
    {
        try {
            $timesheet = DB::table('timesheets')->where('timesheet_id', $id)->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            // Check if timesheet is approved or has payments
            if ($timesheet->status_id == 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete approved timesheet. Please reject it first.'
                ], 400);
            }

            $hasPayments = DB::table('payments')->where('timesheet_id', $id)->exists();
            if ($hasPayments) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete timesheet with existing payments.'
                ], 400);
            }

            // Log activity before deletion
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Timesheet Deleted',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'old_values' => json_encode($timesheet),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()
            ]);

            DB::table('timesheets')->where('timesheet_id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet deleted successfully'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get timesheet statistics
     * GET /api/v1/admin/timesheets/stats
     */
    public function statistics(Request $request)
    {
        try {
            $contractId = $request->input('contract_id');
            $userId = $request->input('user_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = DB::table('timesheets as t')
                       ->leftJoin('contracts as c', 't.contract_id', '=', 'c.contract_id');

            if ($contractId) {
                $query->where('t.contract_id', $contractId);
            }
            if ($userId) {
                $query->where('t.user_id', $userId);
            }
            if ($startDate) {
                $query->where('t.work_date', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('t.work_date', '<=', $endDate);
            }

            $stats = [
                'total_timesheets' => (clone $query)->count(),
                'pending_timesheets' => (clone $query)->where('t.status_id', 1)->count(),
                'approved_timesheets' => (clone $query)->where('t.status_id', 2)->count(),
                'rejected_timesheets' => (clone $query)->where('t.status_id', 3)->count(),
                'total_hours' => (clone $query)->sum('t.work_hours'),
                'total_amount' => (clone $query)->select(DB::raw('SUM(t.work_hours * c.hourly_rate) as total'))->value('total') ?? 0,
                'average_hours_per_timesheet' => (clone $query)->avg('t.work_hours')
            ];

            // Get timesheets by status
            $byStatus = DB::table('timesheets as t')
                          ->leftJoin('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                          ->select('ts.status_name', DB::raw('COUNT(*) as count'))
                          ->groupBy('t.status_id', 'ts.status_name');

            if ($contractId) {
                $byStatus->where('t.contract_id', $contractId);
            }
            if ($userId) {
                $byStatus->where('t.user_id', $userId);
            }
            if ($startDate) {
                $byStatus->where('t.work_date', '>=', $startDate);
            }
            if ($endDate) {
                $byStatus->where('t.work_date', '<=', $endDate);
            }

            $stats['by_status'] = $byStatus->get();

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending timesheets for approval
     * GET /api/v1/admin/timesheets/pending
     */
    public function pendingTimesheets(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 15);
            $companyId = $request->input('company_id');

            $query = DB::table('timesheets as t')
                       ->leftJoin('contracts as c', 't.contract_id', '=', 'c.contract_id')
                       ->leftJoin('projects as p', 'c.project_id', '=', 'p.project_id')
                       ->leftJoin('users as u', 't.user_id', '=', 'u.user_id')
                       ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                       ->leftJoin('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                       ->select(
                           't.*',
                           'c.contract_title',
                           'c.hourly_rate',
                           'p.project_title',
                           'cd.company_name',
                           'u.email as freelancer_email',
                           DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                           'ud.profile_image as freelancer_image',
                           DB::raw("(t.work_hours * c.hourly_rate) as calculated_amount")
                       )
                       ->where('t.status_id', 1); // Pending status

            if ($companyId) {
                $query->where('c.company_id', $companyId);
            }

            $pendingTimesheets = $query->orderBy('t.submitted_at', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Pending timesheets retrieved successfully',
                'data' => $pendingTimesheets
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pending timesheets',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
