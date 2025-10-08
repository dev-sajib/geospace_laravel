<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class TimesheetManagementController extends Controller
{
    /**
     * Get all timesheets (admin view)
     * GET /api/v1/admin/timesheets
     */
    public function index(Request $request)
    {
        try {
            $query = DB::table('timesheets as t')
                ->join('contracts as c', 't.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->select(
                    't.*',
                    'p.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'u.email as freelancer_email',
                    'cd.company_name',
                    'ts.status_name',
                    'c.hourly_rate'
                )
                ->orderBy('t.created_at', 'desc');

            // Filters
            if ($request->has('status')) {
                $query->where('ts.status_name', $request->status);
            }

            if ($request->has('freelancer_id')) {
                $query->where('t.freelancer_id', $request->freelancer_id);
            }

            if ($request->has('company_id')) {
                $query->where('t.company_id', $request->company_id);
            }

            if ($request->has('start_date')) {
                $query->where('t.start_date', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $query->where('t.end_date', '<=', $request->end_date);
            }

            $timesheets = $query->paginate(20);

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
     * Get all accepted timesheets
     * GET /api/v1/admin/timesheets/accepted
     */
    public function acceptedTimesheets()
    {
        try {
            $timesheets = DB::table('timesheets as t')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->leftJoin('invoices as i', 't.timesheet_id', '=', 'i.timesheet_id')
                ->where('ts.status_name', 'Accepted')
                ->select(
                    't.*',
                    'p.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'u.email as freelancer_email',
                    'cd.company_name',
                    'ts.status_name',
                    'i.invoice_id',
                    'i.invoice_number',
                    'i.status as invoice_status'
                )
                ->orderBy('t.reviewed_at', 'desc')
                ->paginate(20);

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
     * Get specific timesheet details
     * GET /api/v1/admin/timesheets/{id}
     */
    public function show($id)
    {
        try {
            $timesheet = DB::table('timesheets as t')
                ->join('contracts as c', 't.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 't.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->leftJoin('users as reviewer', 't.reviewed_by', '=', 'reviewer.user_id')
                ->leftJoin('user_details as reviewer_details', 'reviewer.user_id', '=', 'reviewer_details.user_id')
                ->where('t.timesheet_id', $id)
                ->select(
                    't.*',
                    'p.project_title',
                    'p.project_description',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'u.email as freelancer_email',
                    'ud.phone as freelancer_phone',
                    'cd.company_name',
                    'cd.company_type',
                    'ts.status_name',
                    'c.hourly_rate',
                    'c.contract_title',
                    DB::raw("CONCAT(reviewer_details.first_name, ' ', reviewer_details.last_name) as reviewed_by_name")
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
                    ->orderBy('tdc.created_at', 'desc')
                    ->get();
            }

            // Get invoice if exists
            $invoice = DB::table('invoices')
                ->where('timesheet_id', $id)
                ->first();

            // Get payment requests
            $paymentRequests = DB::table('payment_requests')
                ->where('timesheet_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Get payments
            $payments = DB::table('payments')
                ->where('timesheet_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet retrieved successfully',
                'data' => [
                    'timesheet' => $timesheet,
                    'days' => $days,
                    'invoice' => $invoice,
                    'payment_requests' => $paymentRequests,
                    'payments' => $payments
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
     * Get all payment requests from freelancers
     * GET /api/v1/admin/payment-requests
     */
    public function paymentRequests(Request $request)
    {
        try {
            $query = DB::table('payment_requests as pr')
                ->join('timesheets as t', 'pr.timesheet_id', '=', 't.timesheet_id')
                ->join('invoices as i', 'pr.invoice_id', '=', 'i.invoice_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->join('users as u', 'pr.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('company_details as cd', 't.company_id', '=', 'cd.company_id')
                ->select(
                    'pr.*',
                    't.start_date',
                    't.end_date',
                    't.total_hours',
                    'i.invoice_number',
                    'p.project_title',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'u.email as freelancer_email',
                    'cd.company_name'
                )
                ->orderBy('pr.requested_at', 'desc');

            // Filter by status
            if ($request->has('status')) {
                $query->where('pr.status', $request->status);
            }

            $paymentRequests = $query->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Payment requests retrieved successfully',
                'data' => $paymentRequests
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get company payments for verification
     * GET /api/v1/admin/payments/company-payments
     */
    public function companyPayments(Request $request)
    {
        try {
            $query = DB::table('payments as p')
                ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                ->join('timesheets as t', 'i.timesheet_id', '=', 't.timesheet_id')
                ->join('company_details as cd', 'i.company_id', '=', 'cd.company_id')
                ->join('projects as proj', 't.project_id', '=', 'proj.project_id')
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
                ->where('p.status', 'Pending')
                ->orderBy('p.created_at', 'desc');

            $payments = $query->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Company payments retrieved successfully',
                'data' => $payments
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve company payments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify and approve company payment
     * POST /api/v1/admin/payments/{paymentId}/verify
     */
    public function verifyCompanyPayment(Request $request, $paymentId)
    {
        $validator = Validator::make($request->all(), [
            'verification_notes' => 'nullable|string|max:1000'
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
            $payment = DB::table('payments')
                ->where('payment_id', $paymentId)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // Update payment status to approved
            DB::table('payments')
                ->where('payment_id', $paymentId)
                ->update([
                    'status' => 'Approved',
                    'updated_at' => now()
                ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'action' => 'Company Payment Verified',
                'entity_type' => 'payment',
                'entity_id' => $paymentId,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and approved successfully'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process freelancer payment
     * POST /api/v1/admin/payment-requests/{requestId}/process
     */
    public function processFreelancerPayment(Request $request, $requestId)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|max:255',
            'payment_method' => 'required|string|max:100',
            'payment_notes' => 'nullable|string|max:1000'
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
            $paymentRequest = DB::table('payment_requests as pr')
                ->join('timesheets as t', 'pr.timesheet_id', '=', 't.timesheet_id')
                ->where('pr.request_id', $requestId)
                ->select('pr.*', 't.freelancer_id', 't.total_amount')
                ->first();

            if (!$paymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment request not found'
                ], 404);
            }

            if ($paymentRequest->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment request already processed'
                ], 400);
            }

            // Update payment request status
            DB::table('payment_requests')
                ->where('request_id', $requestId)
                ->update([
                    'status' => 'Completed',
                    'processed_at' => now(),
                    'processed_by' => Auth::id(),
                    'payment_notes' => $request->payment_notes,
                    'updated_at' => now()
                ]);

            // Create or update payment record
            $payment = DB::table('payments')
                ->where('timesheet_id', $paymentRequest->timesheet_id)
                ->where('invoice_id', $paymentRequest->invoice_id)
                ->first();

            if ($payment) {
                DB::table('payments')
                    ->where('payment_id', $payment->payment_id)
                    ->update([
                        'status' => 'Completed',
                        'transaction_id' => $request->transaction_id,
                        'payment_method' => $request->payment_method,
                        'paid_at' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('payments')->insert([
                    'invoice_id' => $paymentRequest->invoice_id,
                    'timesheet_id' => $paymentRequest->timesheet_id,
                    'amount' => $paymentRequest->requested_amount,
                    'currency' => 'CAD',
                    'payment_type' => 'Freelancer Payment',
                    'status' => 'Completed',
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $request->transaction_id,
                    'paid_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Update timesheet payment completed
            DB::table('timesheets')
                ->where('timesheet_id', $paymentRequest->timesheet_id)
                ->update([
                    'payment_completed_at' => now(),
                    'updated_at' => now()
                ]);

            // Update or create freelancer earnings
            $existingEarnings = DB::table('freelancer_earnings')
                ->where('freelancer_id', $paymentRequest->freelancer_id)
                ->first();

            if ($existingEarnings) {
                DB::table('freelancer_earnings')
                    ->where('freelancer_id', $paymentRequest->freelancer_id)
                    ->update([
                        'total_earned' => DB::raw('total_earned + ' . $paymentRequest->requested_amount),
                        'total_paid' => DB::raw('total_paid + ' . $paymentRequest->requested_amount),
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('freelancer_earnings')->insert([
                    'freelancer_id' => $paymentRequest->freelancer_id,
                    'total_earned' => $paymentRequest->requested_amount,
                    'total_paid' => $paymentRequest->requested_amount,
                    'pending_amount' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Notify freelancer
            DB::table('notifications')->insert([
                'user_id' => $paymentRequest->freelancer_id,
                'title' => 'Payment Completed',
                'message' => 'Your payment has been processed successfully',
                'type' => 'Payment',
                'action_url' => '/freelancer/payments/history',
                'is_read' => false,
                'created_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'action' => 'Freelancer Payment Processed',
                'entity_type' => 'payment_request',
                'entity_id' => $requestId,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Freelancer payment processed successfully'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download invoice PDF
     * GET /api/v1/admin/invoices/{invoiceId}/download
     */
    public function downloadInvoice($invoiceId)
    {
        try {
            $invoice = DB::table('invoices as i')
                ->join('timesheets as t', 'i.timesheet_id', '=', 't.timesheet_id')
                ->join('company_details as cd', 'i.company_id', '=', 'cd.company_id')
                ->join('users as u', 'i.freelancer_id', '=', 'u.user_id')
                ->join('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->join('projects as p', 't.project_id', '=', 'p.project_id')
                ->where('i.invoice_id', $invoiceId)
                ->select(
                    'i.*',
                    'cd.company_name',
                    'cd.headquarters as company_address',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name"),
                    'ud.address as freelancer_address',
                    'ud.city',
                    'ud.state',
                    'ud.postal_code',
                    'p.project_title',
                    't.start_date',
                    't.end_date'
                )
                ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            // Get timesheet days
            $days = DB::table('timesheet_days')
                ->where('timesheet_id', $invoice->timesheet_id)
                ->orderBy('day_number')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Invoice data retrieved successfully',
                'data' => [
                    'invoice' => $invoice,
                    'days' => $days
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get timesheet statistics for admin dashboard
     * GET /api/v1/admin/timesheets/statistics
     */
    public function statistics(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = DB::table('timesheets as t')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id');

            if ($startDate) {
                $query->where('t.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('t.created_at', '<=', $endDate);
            }

            $stats = [
                'total_timesheets' => (clone $query)->count(),
                'pending_timesheets' => (clone $query)->where('ts.status_name', 'Pending')->count(),
                'accepted_timesheets' => (clone $query)->where('ts.status_name', 'Accepted')->count(),
                'rejected_timesheets' => (clone $query)->where('ts.status_name', 'Rejected')->count(),
                'total_hours' => (clone $query)->sum('t.total_hours'),
                'total_amount' => (clone $query)->sum('t.total_amount'),
                'pending_payment_requests' => DB::table('payment_requests')
                    ->where('status', 'Pending')
                    ->count(),
                'pending_company_payments' => DB::table('payments')
                    ->where('status', 'Pending')
                    ->count()
            ];

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
     * Delete timesheet (soft delete or hard delete based on status)
     * DELETE /api/v1/admin/timesheets/{id}
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $timesheet = DB::table('timesheets as t')
                ->join('timesheet_status as ts', 't.status_id', '=', 'ts.status_id')
                ->where('t.timesheet_id', $id)
                ->select('t.*', 'ts.status_name')
                ->first();

            if (!$timesheet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timesheet not found'
                ], 404);
            }

            // Check if has payments
            $hasPayments = DB::table('payments')
                ->where('timesheet_id', $id)
                ->exists();

            if ($hasPayments) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete timesheet with existing payments'
                ], 400);
            }

            // Delete related records
            DB::table('timesheet_day_comments')
                ->where('timesheet_id', $id)
                ->delete();

            DB::table('timesheet_days')
                ->where('timesheet_id', $id)
                ->delete();

            DB::table('timesheets')
                ->where('timesheet_id', $id)
                ->delete();

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'action' => 'Timesheet Deleted',
                'entity_type' => 'timesheet',
                'entity_id' => $id,
                'old_values' => json_encode($timesheet),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Timesheet deleted successfully'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete timesheet',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
