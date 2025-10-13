<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        // Payment requests
        $paymentRequests = [
            [
                'request_id' => 1,
                'timesheet_id' => 1, // John Smith, Week 1
                'freelancer_id' => 2, // John Smith
                'requested_amount' => 3570.00,
                'request_notes' => 'Payment for approved timesheet - geological mapping services',
                'status' => 'Paid',
                'requested_at' => '2025-01-22 09:00:00',
                'processed_at' => '2025-01-25 14:30:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 2,
                'timesheet_id' => 2, // John Smith, Week 2
                'freelancer_id' => 2, // John Smith
                'requested_amount' => 3748.50,
                'request_notes' => 'Payment for approved timesheet - advanced geological mapping',
                'status' => 'Paid',
                'requested_at' => '2025-01-29 09:00:00',
                'processed_at' => '2025-02-01 16:45:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 3,
                'timesheet_id' => 3, // Sarah Jones, Week 1
                'freelancer_id' => 3, // Sarah Jones
                'requested_amount' => 3638.25,
                'request_notes' => 'Payment for approved timesheet - geophysical data analysis',
                'status' => 'Paid',
                'requested_at' => '2025-02-08 09:00:00',
                'processed_at' => '2025-02-11 11:20:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 4,
                'timesheet_id' => 4, // Sarah Jones, Week 2
                'freelancer_id' => 3, // Sarah Jones
                'requested_amount' => 3780.00,
                'request_notes' => 'Payment for approved timesheet - advanced geospatial analysis',
                'status' => 'Paid',
                'requested_at' => '2025-02-15 09:00:00',
                'processed_at' => '2025-02-18 14:15:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 5,
                'timesheet_id' => 5, // Michael Brown, Week 1
                'freelancer_id' => 4, // Michael Brown
                'requested_amount' => 3780.00,
                'request_notes' => 'Payment for approved timesheet - geological engineering services',
                'status' => 'Paid',
                'requested_at' => '2025-03-08 09:00:00',
                'processed_at' => '2025-03-11 10:30:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 6,
                'timesheet_id' => 6, // David Wilson, Week 1
                'freelancer_id' => 6, // David Wilson
                'requested_amount' => 3780.00,
                'request_notes' => 'Payment for approved timesheet - environmental impact assessment',
                'status' => 'Paid',
                'requested_at' => '2025-01-08 09:00:00',
                'processed_at' => '2025-01-11 15:45:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 7,
                'timesheet_id' => 7, // Emily Davis, Week 1
                'freelancer_id' => 5, // Emily Davis
                'requested_amount' => 3748.50,
                'request_notes' => 'Payment for approved timesheet - diamond exploration services',
                'status' => 'Paid',
                'requested_at' => '2025-04-08 09:00:00',
                'processed_at' => '2025-04-11 12:00:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 8,
                'timesheet_id' => 8, // Jennifer Taylor, Week 1
                'freelancer_id' => 9, // Jennifer Taylor
                'requested_amount' => 3150.00,
                'request_notes' => 'Payment for approved timesheet - geotechnical investigation',
                'status' => 'Paid',
                'requested_at' => '2025-02-22 09:00:00',
                'processed_at' => '2025-02-25 16:30:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 9,
                'timesheet_id' => 9, // Robert Anderson, Week 1
                'freelancer_id' => 8, // Robert Anderson
                'requested_amount' => 4200.00,
                'request_notes' => 'Payment for approved timesheet - petroleum geology assessment',
                'status' => 'Paid',
                'requested_at' => '2025-01-08 09:00:00',
                'processed_at' => '2025-01-11 14:20:00',
                'processed_by' => 1, // Admin
            ],
            [
                'request_id' => 10,
                'timesheet_id' => 10, // John Smith, REE Project
                'freelancer_id' => 2, // John Smith
                'requested_amount' => 3570.00,
                'request_notes' => 'Payment for approved timesheet - rare earth elements exploration',
                'status' => 'Paid',
                'requested_at' => '2025-03-22 09:00:00',
                'processed_at' => '2025-03-25 11:45:00',
                'processed_by' => 1, // Admin
            ],
            // Pending payment requests
            [
                'request_id' => 11,
                'timesheet_id' => 11, // John Smith, Pending
                'freelancer_id' => 2, // John Smith
                'requested_amount' => 3570.00,
                'request_notes' => 'Payment request for submitted timesheet - geological mapping services',
                'status' => 'Pending',
                'requested_at' => '2025-02-05 09:00:00',
                'processed_at' => null,
                'processed_by' => null,
            ],
            [
                'request_id' => 12,
                'timesheet_id' => 12, // Sarah Jones, Pending
                'freelancer_id' => 3, // Sarah Jones
                'requested_amount' => 3685.50,
                'request_notes' => 'Payment request for submitted timesheet - geophysical data processing',
                'status' => 'Pending',
                'requested_at' => '2025-02-22 09:00:00',
                'processed_at' => null,
                'processed_by' => null,
            ],
            [
                'request_id' => 13,
                'timesheet_id' => 13, // Michael Brown, Pending
                'freelancer_id' => 4, // Michael Brown
                'requested_amount' => 3612.00,
                'request_notes' => 'Payment request for submitted timesheet - geological engineering services',
                'status' => 'Pending',
                'requested_at' => '2025-03-15 09:00:00',
                'processed_at' => null,
                'processed_by' => null,
            ],
        ];

        DB::table('payment_requests')->insert($paymentRequests);

        // Payments
        $payments = [
            [
                'payment_id' => 1,
                'invoice_id' => 1, // John Smith, Week 1
                'timesheet_id' => 1,
                'payment_amount' => 3570.00,
                'payment_date' => '2025-01-25',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-001',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-001.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 2,
                'invoice_id' => 2, // John Smith, Week 2
                'timesheet_id' => 2,
                'payment_amount' => 3748.50,
                'payment_date' => '2025-02-01',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-002',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-002.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 3,
                'invoice_id' => 3, // Sarah Jones, Week 1
                'timesheet_id' => 3,
                'payment_amount' => 3638.25,
                'payment_date' => '2025-02-11',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-003',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-003.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 4,
                'invoice_id' => 4, // Sarah Jones, Week 2
                'timesheet_id' => 4,
                'payment_amount' => 3780.00,
                'payment_date' => '2025-02-18',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-004',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-004.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 5,
                'invoice_id' => 5, // Michael Brown, Week 1
                'timesheet_id' => 5,
                'payment_amount' => 3780.00,
                'payment_date' => '2025-03-11',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-005',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-005.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 6,
                'invoice_id' => 6, // David Wilson, Week 1
                'timesheet_id' => 6,
                'payment_amount' => 3780.00,
                'payment_date' => '2025-01-11',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-006',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-006.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 7,
                'invoice_id' => 7, // Emily Davis, Week 1
                'timesheet_id' => 7,
                'payment_amount' => 3748.50,
                'payment_date' => '2025-04-11',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-007',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-007.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 8,
                'invoice_id' => 8, // Jennifer Taylor, Week 1
                'timesheet_id' => 8,
                'payment_amount' => 3150.00,
                'payment_date' => '2025-02-25',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-008',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-008.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 9,
                'invoice_id' => 9, // Robert Anderson, Week 1
                'timesheet_id' => 9,
                'payment_amount' => 4200.00,
                'payment_date' => '2025-01-11',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-009',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-009.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_id' => 10,
                'invoice_id' => 10, // John Smith, REE Project
                'timesheet_id' => 10,
                'payment_amount' => 3570.00,
                'payment_date' => '2025-03-25',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'TXN-2024-010',
                'status' => 'Completed',
                'payment_notes' => 'Payment processed successfully via bank transfer',
                'payment_receipt' => 'receipts/TXN-2024-010.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payments')->insert($payments);

        // Freelancer earnings
        $freelancerEarnings = [
            [
                'earning_id' => 1,
                'freelancer_id' => 2, // John Smith
                'payment_id' => 1,
                'amount' => 3570.00,
                'earning_date' => '2025-01-25',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for geological mapping and core logging services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 2,
                'freelancer_id' => 2, // John Smith
                'payment_id' => 2,
                'amount' => 3748.50,
                'earning_date' => '2025-02-01',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for advanced geological mapping and analysis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 3,
                'freelancer_id' => 3, // Sarah Jones
                'payment_id' => 3,
                'amount' => 3638.25,
                'earning_date' => '2025-02-11',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for geophysical data analysis services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 4,
                'freelancer_id' => 3, // Sarah Jones
                'payment_id' => 4,
                'amount' => 3780.00,
                'earning_date' => '2025-02-18',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for advanced geospatial analysis services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 5,
                'freelancer_id' => 4, // Michael Brown
                'payment_id' => 5,
                'amount' => 3780.00,
                'earning_date' => '2025-03-11',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for geological engineering services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 6,
                'freelancer_id' => 6, // David Wilson
                'payment_id' => 6,
                'amount' => 3780.00,
                'earning_date' => '2025-01-11',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for environmental impact assessment services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 7,
                'freelancer_id' => 5, // Emily Davis
                'payment_id' => 7,
                'amount' => 3748.50,
                'earning_date' => '2025-04-11',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for diamond exploration services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 8,
                'freelancer_id' => 9, // Jennifer Taylor
                'payment_id' => 8,
                'amount' => 3150.00,
                'earning_date' => '2025-02-25',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for geotechnical investigation services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 9,
                'freelancer_id' => 8, // Robert Anderson
                'payment_id' => 9,
                'amount' => 4200.00,
                'earning_date' => '2025-01-11',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for petroleum geology assessment services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'earning_id' => 10,
                'freelancer_id' => 2, // John Smith
                'payment_id' => 10,
                'amount' => 3570.00,
                'earning_date' => '2025-03-25',
                'earning_type' => 'Timesheet Payment',
                'description' => 'Payment for rare earth elements exploration services',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('freelancer_earnings')->insert($freelancerEarnings);
    }
}
