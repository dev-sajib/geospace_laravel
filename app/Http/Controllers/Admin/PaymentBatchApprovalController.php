<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class PaymentBatchApprovalController extends Controller
{
    /**
     * Get payment list for freelancer payment batch approval
     * Only shows Platform_to_Freelancer payments where freelancer_status = 'pending'
     * GET /api/v1/admin/PaymentToFreelancerList
     */
    public function paymentToFreelancerList(Request $request)
    {
        try {
            $query = DB::table('payments as p')
                ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                ->join('timesheets as t', 'i.timesheet_id', '=', 't.timesheet_id')
                ->join('contracts as c', 'i.contract_id', '=', 'c.contract_id')
                ->join('company_details as cd', 'i.company_id', '=', 'cd.company_id')
                ->join('freelancer_details as fd', 'i.freelancer_id', '=', 'fd.freelancer_detail_id')
                ->select(
                    'p.payment_id as TransactionId',
                    'p.payment_date as PaymentDate',
                    'p.payment_type as TransactionType',
                    'p.amount as Amount',
                    'cd.company_name as FromCompany',
                    DB::raw("CONCAT(fd.first_name, ' ', fd.last_name) as ToFreelancer"),
                    'i.freelancer_status as Status',
                    'i.invoice_number',
                    'c.contract_title',
                    't.start_date',
                    't.end_date',
                    't.total_hours',
                    'p.status as payment_status',
                    'p.transaction_id',
                    'p.verified_by',
                    'p.verified_at',
                    'p.verification_notes'
                )
                ->where('p.payment_type', 'Platform_to_Freelancer')
                ->where('i.freelancer_status', 'pending')
                ->orderBy('p.created_at', 'desc');

            // Optional status filter
            if ($request->has('status') && $request->status) {
                $query->where('p.status', $request->status);
            }

            $perPage = $request->input('per_page', 20);
            $payments = $query->paginate($perPage);

            // Return the data directly as an array to match frontend expectations
            return response()->json($payments->items(), 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment list',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed payment information for processing
     * GET /api/v1/admin/payments/{paymentId}/details
     */
    public function getPaymentDetails($paymentId)
    {
        try {
            // Get payment with all related information
            $payment = DB::table('payments as p')
                ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                ->join('timesheets as t', 'i.timesheet_id', '=', 't.timesheet_id')
                ->join('contracts as c', 'i.contract_id', '=', 'c.contract_id')
                ->join('company_details as cd', 'i.company_id', '=', 'cd.company_id')
                ->join('freelancer_details as fd', 'i.freelancer_id', '=', 'fd.freelancer_detail_id')
                ->select(
                    'p.*',
                    'i.*',
                    'c.contract_title',
                    'c.contract_description',
                    'c.hourly_rate as contract_hourly_rate',
                    'c.start_date as contract_start_date',
                    'c.end_date as contract_end_date',
                    't.start_date',
                    't.end_date',
                    't.total_hours',
                    'cd.company_name',
                    DB::raw("CONCAT(fd.first_name, ' ', fd.last_name) as freelancer_name"),
                    'fd.email as freelancer_email'
                )
                ->where('p.payment_id', $paymentId)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // Get freelancer bank details (primary bank account)
            $bankDetails = DB::table('freelancer_bank_information')
                ->where('freelancer_id', $payment->freelancer_id)
                ->where('is_primary', true)
                ->where('status', 'Active')
                ->first();

            // If no primary bank, get the first active bank
            if (!$bankDetails) {
                $bankDetails = DB::table('freelancer_bank_information')
                    ->where('freelancer_id', $payment->freelancer_id)
                    ->where('status', 'Active')
                    ->first();
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment details retrieved successfully',
                'data' => [
                    'contract' => [
                        'contract_title' => $payment->contract_title,
                        'contract_description' => $payment->contract_description,
                        'hourly_rate' => $payment->contract_hourly_rate,
                        'total_amount' => $payment->total_amount,
                        'start_date' => $payment->contract_start_date,
                        'end_date' => $payment->contract_end_date
                    ],
                    'invoice' => [
                        'invoice_number' => $payment->invoice_number,
                        'invoice_date' => $payment->invoice_date,
                        'total_hours' => $payment->total_hours,
                        'hourly_rate' => $payment->hourly_rate,
                        'subtotal' => $payment->subtotal,
                        'tax_amount' => $payment->tax_amount,
                        'total_amount' => $payment->total_amount,
                        'due_date' => $payment->due_date,
                        'status' => $payment->status,
                        'freelancer_status' => $payment->freelancer_status,
                        'company_status' => $payment->company_status
                    ],
                    'bank_details' => $bankDetails
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process freelancer payment
     * POST /api/v1/admin/payments/{paymentId}/process
     */
    public function processPayment(Request $request, $paymentId)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|max:255',
            'verification_notes' => 'nullable|string|max:1000',
            'verified_by' => 'required|integer'
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
                ->where('payment_type', 'Platform_to_Freelancer')
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            if ($payment->status === 'Completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment already processed'
                ], 400);
            }

            // Update payment status
            DB::table('payments')
                ->where('payment_id', $paymentId)
                ->update([
                    'status' => 'Completed',
                    'transaction_id' => $request->transaction_id,
                    'payment_date' => now(),
                    'verified_by' => $request->verified_by,
                    'verified_at' => now(),
                    'verification_notes' => $request->verification_notes,
                    'updated_at' => now()
                ]);

            // Update invoice freelancer_status to complete
            DB::table('invoices')
                ->where('invoice_id', $payment->invoice_id)
                ->update([
                    'freelancer_status' => 'complete',
                    'paid_at' => now(),
                    'updated_at' => now()
                ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => Auth::id(),
                'action' => 'Freelancer Payment Processed',
                'entity_type' => 'payment',
                'entity_id' => $paymentId,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            // Get freelancer user_id from payment
            $paymentDetails = DB::table('payments as p')
                ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                ->join('freelancer_details as fd', 'i.freelancer_id', '=', 'fd.freelancer_detail_id')
                ->where('p.payment_id', $paymentId)
                ->select('fd.user_id')
                ->first();

            if ($paymentDetails) {
                // Notify freelancer
                DB::table('notifications')->insert([
                    'user_id' => $paymentDetails->user_id,
                    'title' => 'Payment Processed',
                    'message' => 'Your payment has been processed and sent to your bank account.',
                    'type' => 'Success',
                    'action_url' => '/freelancer/earnings/overview',
                    'is_read' => false,
                    'created_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully'
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
     * Get payments statistics
     * GET /api/v1/admin/payments/stats
     */
    public function paymentsStats(Request $request)
    {
        try {
            $stats = [
                'total_freelancer_payments' => DB::table('payments')
                    ->where('payment_type', 'Platform_to_Freelancer')
                    ->count(),
                'pending_freelancer_payments' => DB::table('payments as p')
                    ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                    ->where('p.payment_type', 'Platform_to_Freelancer')
                    ->where('i.freelancer_status', 'pending')
                    ->count(),
                'completed_freelancer_payments' => DB::table('payments')
                    ->where('payment_type', 'Platform_to_Freelancer')
                    ->where('status', 'Completed')
                    ->count(),
                'total_pending_amount' => DB::table('payments as p')
                    ->join('invoices as i', 'p.invoice_id', '=', 'i.invoice_id')
                    ->where('p.payment_type', 'Platform_to_Freelancer')
                    ->where('i.freelancer_status', 'pending')
                    ->sum('p.amount'),
                'total_completed_amount' => DB::table('payments')
                    ->where('payment_type', 'Platform_to_Freelancer')
                    ->where('status', 'Completed')
                    ->sum('amount')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Payment statistics retrieved successfully',
                'data' => $stats
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}