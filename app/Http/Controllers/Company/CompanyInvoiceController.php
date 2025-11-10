<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyInvoiceController extends Controller
{
    /**
     * Get company invoices with filters
     */
    public function getInvoices(Request $request)
    {
        try {
            $user = Auth::user();
            $user->load('companyDetails'); // Eager load companyDetails
            $companyId = $user->companyDetails->company_id ?? null;

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company details not found'
                ], 404);
            }

            $query = Invoice::with([
                'contract.project',
                'freelancer.freelancerDetails',
                'timesheet'
            ])->where('company_id', $companyId);

            // Filter by invoice type
            if ($request->has('invoice_type')) {
                switch ($request->invoice_type) {
                    case 'Pending Payments':
                        $query->whereIn('status', ['Generated', 'Sent']);
                        break;
                    case 'Completed Payments':
                        $query->where('status', 'Paid');
                        break;
                    case 'All Invoices':
                        // No filter, show all
                        break;
                }
            }

            // Filter by year
            if ($request->has('year')) {
                $query->whereYear('invoice_date', $request->year);
            }

            // Filter by month
            if ($request->has('month')) {
                $monthNumber = $this->getMonthNumber($request->month);
                if ($monthNumber) {
                    $query->whereMonth('invoice_date', $monthNumber);
                }
            }

            $invoices = $query->orderBy('invoice_date', 'desc')->get();

            // Format the data for frontend
            $formattedInvoices = $invoices->map(function ($invoice) use ($user) {
                $freelancerName = 'Unknown Freelancer';
                if ($invoice->freelancer && $invoice->freelancer->freelancerDetails) {
                    $freelancerName = trim(
                        ($invoice->freelancer->freelancerDetails->first_name ?? '') . ' ' .
                        ($invoice->freelancer->freelancerDetails->last_name ?? '')
                    ) ?: 'Unknown Freelancer';
                }

                return [
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_number' => $invoice->invoice_number,
                    'projectName' => $invoice->contract->project->project_title ?? 'N/A',
                    'freelancerName' => $freelancerName,
                    'companyName' => $user->companyDetails->company_name ?? 'N/A',
                    'amount' => '$' . number_format($invoice->total_amount, 2),
                    'amountRaw' => $invoice->total_amount,
                    'currency' => $invoice->currency,
                    'dueDate' => $invoice->due_date ? date('d/m/y', strtotime($invoice->due_date)) : 'N/A',
                    'dueDateRaw' => $invoice->due_date,
                    'invoiceDate' => date('d/m/y', strtotime($invoice->invoice_date)),
                    'invoiceDateRaw' => $invoice->invoice_date,
                    'paymentStatus' => $invoice->status,
                    'total_hours' => $invoice->total_hours,
                    'hourly_rate' => $invoice->hourly_rate,
                    'subtotal' => $invoice->subtotal,
                    'tax_amount' => $invoice->tax_amount,
                    'paid_at' => $invoice->paid_at ? date('d/m/y', strtotime($invoice->paid_at)) : null,
                    'sent_at' => $invoice->sent_at,
                    // Additional data for PDF generation
                    'freelancerEmail' => $invoice->freelancer->email ?? '',
                    'freelancerPhone' => $invoice->freelancer->freelancerDetails->phone ?? '',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedInvoices,
                'total' => $invoices->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching company invoices: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch invoices'
            ], 500);
        }
    }

    /**
     * Update invoice status (mark as paid)
     */
    public function updateInvoiceStatus(Request $request)
    {
        try {
            $request->validate([
                'invoice_id' => 'required|integer',
                'status' => 'required|in:Paid,Generated,Sent,Overdue,Cancelled'
            ]);

            $user = Auth::user();
            $companyId = $user->companyDetails->company_id ?? null;

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company details not found'
                ], 404);
            }

            $invoice = Invoice::where('invoice_id', $request->invoice_id)
                           ->where('company_id', $companyId)
                           ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            // Update invoice status
            $updateData = ['status' => $request->status];

            // If marking as paid, set paid_at timestamp
            if ($request->status === 'Paid') {
                $updateData['paid_at'] = now();
            }

            $invoice->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Invoice status updated successfully',
                'data' => $invoice
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating invoice status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice status'
            ], 500);
        }
    }

    /**
     * Get invoice details for PDF generation
     */
    public function getInvoiceDetails(Request $request)
    {
        try {
            $request->validate([
                'invoice_id' => 'required|integer'
            ]);

            $user = Auth::user();
            $companyId = $user->companyDetails->company_id ?? null;

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company details not found'
                ], 404);
            }

            $invoice = Invoice::with([
                'contract.project',
                'freelancer.freelancerDetails',
                'timesheet'
            ])->where('invoice_id', $request->invoice_id)
              ->where('company_id', $companyId)
              ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            $freelancerName = 'Unknown Freelancer';
            if ($invoice->freelancer && $invoice->freelancer->freelancerDetails) {
                $freelancerName = trim(
                    ($invoice->freelancer->freelancerDetails->first_name ?? '') . ' ' .
                    ($invoice->freelancer->freelancerDetails->last_name ?? '')
                ) ?: 'Unknown Freelancer';
            }

            $invoiceData = [
                'invoice_id' => $invoice->invoice_id,
                'invoice_number' => $invoice->invoice_number,
                'projectName' => $invoice->contract->project->project_title ?? 'N/A',
                'freelancerName' => $freelancerName,
                'freelancerEmail' => $invoice->freelancer->email ?? '',
                'freelancerPhone' => $invoice->freelancer->freelancerDetails->phone ?? '',
                'companyName' => $user->companyDetails->company_name ?? 'N/A',
                'amountRaw' => $invoice->total_amount,
                'currency' => $invoice->currency,
                'dueDate' => $invoice->due_date ? date('Y-m-d', strtotime($invoice->due_date)) : null,
                'invoiceDate' => date('Y-m-d', strtotime($invoice->invoice_date)),
                'paymentStatus' => $invoice->status,
                'total_hours' => $invoice->total_hours,
                'hourly_rate' => $invoice->hourly_rate,
                'subtotal' => $invoice->subtotal,
                'tax_amount' => $invoice->tax_amount,
                'paid_at' => $invoice->paid_at ? date('Y-m-d', strtotime($invoice->paid_at)) : null,
            ];

            return response()->json([
                'success' => true,
                'data' => $invoiceData
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching invoice details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch invoice details'
            ], 500);
        }
    }

    /**
     * Helper function to convert month name to number
     */
    private function getMonthNumber($monthName)
    {
        $months = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12,
        ];

        return $months[$monthName] ?? null;
    }

    /**
     * Get upcoming payments (invoices ready for payment processing)
     */
    public function getUpcomingPayments(Request $request)
    {
        try {
            $user = Auth::user();
            $user->load('companyDetails'); // Eager load companyDetails
            $companyId = $user->companyDetails->company_id ?? null;

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company details not found'
                ], 404);
            }

            // Get invoices that need payment processing (Generated or Sent status)
            $query = Invoice::with([
                'contract.project',
                'freelancer.freelancerDetails',
                'timesheet'
            ])->where('company_id', $companyId)
              ->whereIn('status', ['Generated', 'Sent']);

            // Filter by year if provided
            if ($request->has('year')) {
                $query->whereYear('invoice_date', $request->year);
            }

            // Filter by month if provided
            if ($request->has('month')) {
                $monthNumber = $this->getMonthNumber($request->month);
                if ($monthNumber) {
                    $query->whereMonth('invoice_date', $monthNumber);
                }
            }

            $invoices = $query->orderBy('due_date', 'asc')->get();

            // Format the data for frontend
            $formattedInvoices = $invoices->map(function ($invoice) use ($user) {
                $freelancerName = 'Unknown Freelancer';
                if ($invoice->freelancer && $invoice->freelancer->freelancerDetails) {
                    $freelancerName = trim(
                        ($invoice->freelancer->freelancerDetails->first_name ?? '') . ' ' .
                        ($invoice->freelancer->freelancerDetails->last_name ?? '')
                    ) ?: 'Unknown Freelancer';
                }

                $daysPastDue = 0;
                $isOverdue = false;
                if ($invoice->due_date) {
                    $dueDate = new \DateTime($invoice->due_date);
                    $today = new \DateTime();
                    $daysPastDue = max(0, $today->diff($dueDate)->days);
                    $isOverdue = $today > $dueDate;
                }

                return [
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_number' => $invoice->invoice_number,
                    'projectName' => $invoice->contract->project->project_title ?? 'N/A',
                    'freelancerName' => $freelancerName,
                    'freelancerEmail' => $invoice->freelancer->email ?? '',
                    'amount' => '$' . number_format($invoice->total_amount, 2),
                    'amountRaw' => $invoice->total_amount,
                    'currency' => $invoice->currency,
                    'dueDate' => $invoice->due_date ? date('M d, Y', strtotime($invoice->due_date)) : 'N/A',
                    'dueDateRaw' => $invoice->due_date,
                    'invoiceDate' => date('M d, Y', strtotime($invoice->invoice_date)),
                    'paymentStatus' => $invoice->status,
                    'isOverdue' => $isOverdue,
                    'daysPastDue' => $daysPastDue,
                    'total_hours' => $invoice->total_hours,
                    'hourly_rate' => $invoice->hourly_rate,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedInvoices,
                'total' => $invoices->count(),
                'totalAmount' => $invoices->sum('total_amount')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching upcoming payments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch upcoming payments'
            ], 500);
        }
    }

    /**
     * Process payment for an invoice
     */
    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'invoice_id' => 'required|integer|exists:invoices,invoice_id',
                'payment_method' => 'required|string|max:100',
                'transaction_id' => 'required|string|max:255',
                'verification_notes' => 'nullable|string'
            ]);

            $user = Auth::user();
            $companyId = $user->companyDetails->company_id ?? null;

            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company details not found'
                ], 404);
            }

            // Get the invoice
            $invoice = Invoice::where('invoice_id', $request->invoice_id)
                           ->where('company_id', $companyId)
                           ->first();

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            // Check if invoice is already paid
            if ($invoice->status === 'Paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice is already paid'
                ], 400);
            }

            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->invoice_id,
                'timesheet_id' => $invoice->timesheet_id,
                'payment_type' => 'Company_to_Platform',
                'amount' => $invoice->total_amount,
                'currency' => $invoice->currency,
                'status' => 'Completed',
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'verified_by' => $user->user_id,
                'verified_at' => now(),
                'verification_notes' => $request->verification_notes
            ]);

            // Update invoice status to Paid
            $invoice->update([
                'status' => 'Paid',
                'paid_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'data' => [
                    'payment_id' => $payment->payment_id,
                    'invoice_id' => $invoice->invoice_id,
                    'amount' => $payment->amount,
                    'transaction_id' => $payment->transaction_id,
                    'payment_date' => $payment->payment_date->format('Y-m-d H:i:s')
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error processing payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment'
            ], 500);
        }
    }
}