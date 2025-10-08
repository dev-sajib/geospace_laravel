<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class CompanyTimesheetController extends Controller
{
    /**
     * Get all timesheets for company
     * GET /api/v1/company/timesheets
     */
    public function index(Request $request)
    {
        try {
            $companyUserId = Auth::id();
            
            // Get company_id from user
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company profile not found'
                ], 404);
            }

            $query = DB::table('timesheets as t')
                ->join('contracts as c', 't.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.company_id', $companyId)
                ->select(
                    't.*',
                    'p.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'ud.profile_image as freelancer_image',
                    'u.email as freelancer_email',
                    'ts.status_name'
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
     * Get pending timesheets for review
     * GET /api/v1/company/timesheets/pending
     */
    public function pendingTimesheets()
    {
        try {
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            $timesheets = DB::table('timesheets as t')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.company_id', $companyId)
                ->where('ts.status_name', 'Pending')
                ->select(
                    't.*',
                    'p.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'ud.profile_image as freelancer_image',
                    'ts.status_name'
                )
                ->orderBy('t.submitted_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Pending timesheets retrieved successfully',
                'data' => $timesheets
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pending timesheets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific timesheet details with all days and comments
     * GET /api/v1/company/timesheets/{id}
     */
    public function show($id)
    {
        try {
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            // Get timesheet with all details
            $timesheet = DB::table('timesheets as t')
                ->join('contracts as c', 't.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.timesheet_id', $id)
                ->where('t.company_id', $companyId)
                ->select(
                    't.*',
                    'p.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'ud.profile_image as freelancer_image',
                    'u.email as freelancer_email',
                    'ud.phone as freelancer_phone',
                    'ts.status_name',
                    'c.hourly_rate'
                )
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found or access denied'
                ], 404);
            }

            // Get 7 days data with comments
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
                    ->orderBy('tdc.created_at', 'desc')
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
     * Add company comment to specific day
     * POST /api/v1/company/timesheets/{id}/days/{dayId}/comment
     */
    public function addDayComment(Request $request, $id, $dayId)
    {
        $validator = Validator::make($request->all(), [
            'comment_text' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            // Verify timesheet belongs to company
            $timesheet = DB::table('timesheets')
                ->where('timesheet_id', $id)
                ->where('company_id', $companyId)
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found or access denied'
                ], 404);
            }

            // Verify day belongs to timesheet
            $day = DB::table('timesheet_days')
                ->where('day_id', $dayId)
                ->where('timesheet_id', $id)
                ->first();

            if (!$day) {
                return response()->json([
                    'success' => false,
                    'message' => 'Day not found'
                ], 404);
            }

            // Add comment
            $commentId = DB::table('timesheet_day_comments')->insertGetId([
                'day_id' => $dayId,
                'timesheet_id' => $id,
                'comment_by' => $companyUserId,
                'comment_type' => 'Company',
                'comment_text' => $request->comment_text,
                'created_at' => now()
            ]);

            // Get comment with user details
            $comment = DB::table('timesheet_day_comments as tdc')
                ->join('users as u', 'tdc.comment_by', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->where('tdc.comment_id', $commentId)
                ->select(
                    'tdc.*',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as commenter_name")
                )
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $comment
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept timesheet and generate invoice
     * POST /api/v1/company/timesheets/{id}/accept
     */
    public function accept(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            // Verify timesheet
            $timesheet = DB::table('timesheets as t')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.timesheet_id', $id)
                ->where('t.company_id', $companyId)
                ->select('t.*', 'ts.status_name')
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found or access denied'
                ], 404);
            }

            if ($timesheet->status_name !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending timesheets can be accepted'
                ], 400);
            }

            // Update timesheet status to Accepted
            $acceptedStatus = DB::table('timesheet_status')
                ->where('status_name', 'Accepted')
                ->first();

            DB::table('timesheets')
                ->where('timesheet_id', $id)
                ->update([
                    'status_id' => $acceptedStatus->status_id,
                    'status_display_name' => $acceptedStatus->status_name,
                    'reviewed_at' => now(),
                    'reviewed_by' => $companyUserId,
                    'updated_at' => now()
                ]);

            // Generate invoice
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            
            $invoiceId = DB::table('invoices')->insertGetId([
                'timesheet_id' => $id,
                'contract_id' => $timesheet->contract_id,
                'company_id' => $companyId,
                'freelancer_id' => $timesheet->freelancer_id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now()->toDateString(),
                'total_hours' => $timesheet->total_hours,
                'hourly_rate' => $timesheet->hourly_rate,
                'subtotal' => $timesheet->total_amount,
                'tax_amount' => 0,
                'total_amount' => $timesheet->total_amount,
                'currency' => 'CAD',
                'status' => 'Generated',
                'due_date' => now()->addDays(30)->toDateString(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Notify freelancer
            DB::table('notifications')->insert([
                'user_id' => $timesheet->freelancer_id,
                'title' => 'Timesheet Accepted',
                'message' => 'Your timesheet has been accepted by the company',
                'type' => 'Timesheet',
                'action_url' => "/freelancer/timesheets/{$id}",
                'is_read' => false,
                'created_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $companyUserId,
                'action' => 'Timesheet Accepted',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet accepted and invoice generated',
                'data' => [
                    'timesheet_id' => $id,
                    'invoice_id' => $invoiceId,
                    'invoice_number' => $invoiceNumber
                ]
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject timesheet with reason
     * POST /api/v1/company/timesheets/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'nullable|string|max:1000'
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
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            // Verify timesheet
            $timesheet = DB::table('timesheets as t')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.timesheet_id', $id)
                ->where('t.company_id', $companyId)
                ->select('t.*', 'ts.status_name')
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found or access denied'
                ], 404);
            }

            if ($timesheet->status_name !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending timesheets can be rejected'
                ], 400);
            }

            // Update timesheet status to Rejected
            $rejectedStatus = DB::table('timesheet_status')
                ->where('status_name', 'Rejected')
                ->first();

            DB::table('timesheets')
                ->where('timesheet_id', $id)
                ->update([
                    'status_id' => $rejectedStatus->status_id,
                    'status_display_name' => $rejectedStatus->status_name,
                    'reviewed_at' => now(),
                    'reviewed_by' => $companyUserId,
                    'updated_at' => now()
                ]);

            // Notify freelancer
            DB::table('notifications')->insert([
                'user_id' => $timesheet->freelancer_id,
                'title' => 'Timesheet Rejected',
                'message' => 'Your timesheet has been rejected. Please review and resubmit.',
                'type' => 'Timesheet',
                'action_url' => "/freelancer/timesheets/{$id}",
                'is_read' => false,
                'created_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $companyUserId,
                'action' => 'Timesheet Rejected',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'new_values' => json_encode(['rejection_reason' => $request->rejection_reason]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet rejected successfully'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get accepted timesheets with invoices
     * GET /api/v1/company/timesheets/accepted
     */
    public function acceptedTimesheets()
    {
        try {
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            $timesheets = DB::table('timesheets as t')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->leftJoin('invoices as i', 't.timesheet_id', '=', 'i.timesheet_id')
                ->where('t.company_id', $companyId)
                ->where('ts.status_name', 'Accepted')
                ->select(
                    't.*',
                    'p.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'ts.status_name',
                    'i.invoice_id',
                    'i.invoice_number',
                    'i.status as invoice_status',
                    'i.due_date'
                )
                ->orderBy('t.reviewed_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Accepted timesheets retrieved successfully',
                'data' => $timesheets
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve accepted timesheets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete payment for invoice
     * POST /api/v1/company/invoices/{invoiceId}/complete-payment
     */
    public function completePayment(Request $request, $invoiceId)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|max:255',
            'payment_method' => 'required|string|max:100'
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
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            // Verify invoice belongs to company
            $invoice = DB::table('invoices')
                ->where('invoice_id', $invoiceId)
                ->where('company_id', $companyId)
                ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found or access denied'
                ], 404);
            }

            if ($invoice->status === 'Paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice already paid'
                ], 400);
            }

            // Update invoice status
            DB::table('invoices')
                ->where('invoice_id', $invoiceId)
                ->update([
                    'status' => 'Paid',
                    'paid_at' => now(),
                    'updated_at' => now()
                ]);

            // Create payment record
            DB::table('payments')->insert([
                'invoice_id' => $invoiceId,
                'timesheet_id' => $invoice->timesheet_id,
                'contract_id' => $invoice->contract_id,
                'amount' => $invoice->total_amount,
                'currency' => $invoice->currency,
                'payment_type' => 'Timesheet Payment',
                'status' => 'Pending',
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'paid_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Notify admin for verification
            $adminUsers = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.role_id')
                ->where('roles.role_name', 'Admin')
                ->pluck('users.user_id');

            foreach ($adminUsers as $adminId) {
                DB::table('notifications')->insert([
                    'user_id' => $adminId,
                    'title' => 'Payment Completed',
                    'message' => 'Company has completed payment for invoice ' . $invoice->invoice_number,
                    'type' => 'Payment',
                    'action_url' => "/admin/payments/verify",
                    'is_read' => false,
                    'created_at' => now()
                ]);
            }

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => $companyUserId,
                'action' => 'Payment Completed',
                'entity_type' => 'invoice',
                'entity_id' => $invoiceId,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment completed and submitted for verification'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get company payment history
     * GET /api/v1/company/payments/history
     */
    public function paymentHistory()
    {
        try {
            $companyUserId = Auth::id();
            
            $companyId = DB::table('company_details')
                ->where('user_id', $companyUserId)
                ->value('company_id');

            $payments = DB::table('payments as p')
                ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                ->join('timesheets as t', 'i.timesheet_id', '=', 't.timesheet_id')
                ->join('projects as proj', 't.project_id', '=', 'proj.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->where('i.company_id', $companyId)
                ->select(
                    'p.*',
                    'i.invoice_number',
                    't.start_date',
                    't.end_date',
                    't.total_hours',
                    'proj.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name")
                )
                ->orderBy('p.created_at', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'Payment history retrieved successfully',
                'data' => $payments
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
