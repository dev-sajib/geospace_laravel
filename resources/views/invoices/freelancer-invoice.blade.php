<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 40px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }

        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 3px solid #0B8468;
            padding-bottom: 20px;
        }

        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .company-logo {
            font-size: 32px;
            font-weight: bold;
            color: #0B8468;
            margin-bottom: 10px;
        }

        .invoice-title {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .invoice-title h1 {
            font-size: 36px;
            color: #0B8468;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }

        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }

        .bill-to, .invoice-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .bill-to {
            padding-right: 20px;
        }

        .invoice-info {
            text-align: right;
        }

        .section-title {
            font-size: 12px;
            color: #0B8468;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .detail-text {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .detail-text strong {
            display: inline-block;
            width: 120px;
        }

        .items-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .items-table thead {
            background-color: #0B8468;
            color: white;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #0B8468;
        }

        .text-right {
            text-align: right;
        }

        .totals-section {
            margin-left: auto;
            width: 350px;
            margin-top: 20px;
        }

        .total-row {
            display: table;
            width: 100%;
            padding: 8px 0;
        }

        .total-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
            font-size: 14px;
        }

        .total-value {
            display: table-cell;
            text-align: right;
            font-size: 14px;
            width: 150px;
        }

        .grand-total {
            background-color: #0B8468;
            color: white;
            padding: 15px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .grand-total .total-label,
        .grand-total .total-value {
            font-size: 18px;
            font-weight: bold;
        }

        .payment-info {
            background-color: #f8f9fa;
            padding: 20px;
            margin-top: 40px;
            border-radius: 5px;
            border-left: 4px solid #0B8468;
        }

        .payment-info .section-title {
            margin-bottom: 15px;
        }

        .payment-info p {
            font-size: 13px;
            margin-bottom: 8px;
            line-height: 1.8;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <div class="company-logo">GeoSpace</div>
                <p style="font-size: 12px; color: #666;">Professional Freelance Services</p>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p class="invoice-number">{{ $invoice->invoice_number }}</p>
                @if($invoice->status == 'Paid')
                    <span class="status-badge status-paid">Paid</span>
                @elseif(in_array($invoice->status, ['Generated', 'Sent']))
                    <span class="status-badge status-pending">Pending</span>
                @elseif($invoice->status == 'Overdue')
                    <span class="status-badge status-overdue">Overdue</span>
                @endif
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="bill-to">
                <div class="section-title">Bill To</div>
                <div class="detail-text"><strong>{{ $company->CompanyName }}</strong></div>
                @if($company->Address)
                    <div class="detail-text">{{ $company->Address }}</div>
                @endif
                @if($company->City && $company->Province)
                    <div class="detail-text">{{ $company->City }}, {{ $company->Province }}</div>
                @endif
                @if($company->PostalCode)
                    <div class="detail-text">{{ $company->PostalCode }}</div>
                @endif
            </div>
            <div class="invoice-info">
                <div class="section-title">Invoice Details</div>
                <div class="detail-text"><strong>Invoice Date:</strong> {{ date('F d, Y', strtotime($invoice->invoice_date)) }}</div>
                @if($invoice->due_date)
                    <div class="detail-text"><strong>Due Date:</strong> {{ date('F d, Y', strtotime($invoice->due_date)) }}</div>
                @endif
                <div class="detail-text"><strong>Project:</strong> {{ $project->title }}</div>
                @if($invoice->paid_at)
                    <div class="detail-text"><strong>Paid On:</strong> {{ date('F d, Y', strtotime($invoice->paid_at)) }}</div>
                @endif
            </div>
        </div>

        <!-- From Section -->
        <div style="margin-bottom: 40px;">
            <div class="section-title">From</div>
            <div class="detail-text"><strong>{{ $freelancer->FirstName }} {{ $freelancer->LastName }}</strong></div>
            @if($freelancer->Email)
                <div class="detail-text">Email: {{ $freelancer->Email }}</div>
            @endif
            @if($freelancer->PhoneNumber)
                <div class="detail-text">Phone: {{ $freelancer->PhoneNumber }}</div>
            @endif
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Hours</th>
                    <th class="text-right">Rate</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $project->title }}</strong><br>
                        <span style="font-size: 12px; color: #666;">
                            Professional services for the period ending {{ date('F d, Y', strtotime($invoice->invoice_date)) }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($invoice->total_hours, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->hourly_rate, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-value">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</div>
            </div>
            @if($invoice->tax_amount > 0)
                <div class="total-row">
                    <div class="total-label">Tax:</div>
                    <div class="total-value">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</div>
                </div>
            @endif
            <div class="total-row grand-total">
                <div class="total-label">Total Amount:</div>
                <div class="total-value">{{ $invoice->currency }} {{ number_format($invoice->total_amount, 2) }}</div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-info">
            <div class="section-title">Payment Information</div>
            @if($invoice->status == 'Paid')
                <p><strong>Payment Status:</strong> This invoice has been paid in full.</p>
                <p><strong>Payment Date:</strong> {{ date('F d, Y', strtotime($invoice->paid_at)) }}</p>
            @else
                <p><strong>Payment Terms:</strong> Payment is due within 30 days of invoice date.</p>
                @if($invoice->due_date)
                    <p><strong>Due Date:</strong> {{ date('F d, Y', strtotime($invoice->due_date)) }}</p>
                @endif
                <p>Please make payment to the bank account details provided separately or contact us for payment arrangements.</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p style="margin-top: 10px;">This is a computer-generated invoice and does not require a signature.</p>
            <p style="margin-top: 5px;">For any queries regarding this invoice, please contact support@geospace.com</p>
        </div>
    </div>
</body>
</html>
