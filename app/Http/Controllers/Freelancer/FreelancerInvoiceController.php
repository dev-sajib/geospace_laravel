<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FreelancerInvoiceController extends Controller
{
    /**
     * Get freelancer invoices with filters
     */
    public function getInvoices(Request $request)
    {
        try {
            $user = Auth::user();
            $freelancerId = $user->user_id;

            $query = Invoice::with([
                'contract.project',
                'company.companyInfo',
                'timesheet'
            ])->where('freelancer_id', $freelancerId);

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
                return [
                    'invoice_id' => $invoice->invoice_id,
                    'invoice_number' => $invoice->invoice_number,
                    'projectName' => $invoice->contract->project->title ?? 'N/A',
                    'companyName' => $invoice->company->companyInfo->CompanyName ?? 'N/A',
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
                    'freelancerName' => ($user->FirstName ?? '') . ' ' . ($user->LastName ?? ''),
                    'freelancerEmail' => $user->Email ?? '',
                    'freelancerPhone' => $user->PhoneNumber ?? '',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedInvoices,
                'total' => $invoices->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching freelancer invoices: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch invoices'
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
}
