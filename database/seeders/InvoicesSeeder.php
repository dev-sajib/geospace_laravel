<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoicesSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = [
            [
                'invoice_id' => 1,
                'timesheet_id' => 1, // John Smith, Week 1
                'company_id' => 10, // Northern Mining Corp
                'freelancer_id' => 2, // John Smith
                'invoice_number' => 'INV-2024-001',
                'invoice_date' => '2024-01-22',
                'due_date' => '2024-02-21',
                'subtotal' => 3400.00,
                'tax_amount' => 170.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3570.00,
                'status' => 'Paid',
                'notes' => 'Payment for geological mapping and core logging services',
                'invoice_pdf' => 'invoices/INV-2024-001.pdf',
                'paid_at' => '2024-01-25 14:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 2,
                'timesheet_id' => 2, // John Smith, Week 2
                'company_id' => 10, // Northern Mining Corp
                'freelancer_id' => 2, // John Smith
                'invoice_number' => 'INV-2024-002',
                'invoice_date' => '2024-01-29',
                'due_date' => '2024-02-28',
                'subtotal' => 3570.00,
                'tax_amount' => 178.50,
                'tax_percentage' => 5.00,
                'total_amount' => 3748.50,
                'status' => 'Paid',
                'notes' => 'Payment for advanced geological mapping and analysis',
                'invoice_pdf' => 'invoices/INV-2024-002.pdf',
                'paid_at' => '2024-02-01 16:45:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 3,
                'timesheet_id' => 3, // Sarah Jones, Week 1
                'company_id' => 11, // GeoData Analytics
                'freelancer_id' => 3, // Sarah Jones
                'invoice_number' => 'INV-2024-003',
                'invoice_date' => '2024-02-08',
                'due_date' => '2024-03-09',
                'subtotal' => 3465.00,
                'tax_amount' => 173.25,
                'tax_percentage' => 5.00,
                'total_amount' => 3638.25,
                'status' => 'Paid',
                'notes' => 'Payment for geophysical data analysis services',
                'invoice_pdf' => 'invoices/INV-2024-003.pdf',
                'paid_at' => '2024-02-11 11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 4,
                'timesheet_id' => 4, // Sarah Jones, Week 2
                'company_id' => 11, // GeoData Analytics
                'freelancer_id' => 3, // Sarah Jones
                'invoice_number' => 'INV-2024-004',
                'invoice_date' => '2024-02-15',
                'due_date' => '2024-03-16',
                'subtotal' => 3600.00,
                'tax_amount' => 180.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3780.00,
                'status' => 'Paid',
                'notes' => 'Payment for advanced geospatial analysis services',
                'invoice_pdf' => 'invoices/INV-2024-004.pdf',
                'paid_at' => '2024-02-18 14:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 5,
                'timesheet_id' => 5, // Michael Brown, Week 1
                'company_id' => 12, // Exploration Corp International
                'freelancer_id' => 4, // Michael Brown
                'invoice_number' => 'INV-2024-005',
                'invoice_date' => '2024-03-08',
                'due_date' => '2024-04-07',
                'subtotal' => 3600.00,
                'tax_amount' => 180.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3780.00,
                'status' => 'Paid',
                'notes' => 'Payment for geological engineering services',
                'invoice_pdf' => 'invoices/INV-2024-005.pdf',
                'paid_at' => '2024-03-11 10:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 6,
                'timesheet_id' => 6, // David Wilson, Week 1
                'company_id' => 13, // Geo Services Ltd
                'freelancer_id' => 6, // David Wilson
                'invoice_number' => 'INV-2024-006',
                'invoice_date' => '2024-01-08',
                'due_date' => '2024-02-07',
                'subtotal' => 3600.00,
                'tax_amount' => 180.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3780.00,
                'status' => 'Paid',
                'notes' => 'Payment for environmental impact assessment services',
                'invoice_pdf' => 'invoices/INV-2024-006.pdf',
                'paid_at' => '2024-01-11 15:45:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 7,
                'timesheet_id' => 7, // Emily Davis, Week 1
                'company_id' => 14, // Mineral Solutions Inc
                'freelancer_id' => 5, // Emily Davis
                'invoice_number' => 'INV-2024-007',
                'invoice_date' => '2024-04-08',
                'due_date' => '2024-05-08',
                'subtotal' => 3570.00,
                'tax_amount' => 178.50,
                'tax_percentage' => 5.00,
                'total_amount' => 3748.50,
                'status' => 'Paid',
                'notes' => 'Payment for diamond exploration services',
                'invoice_pdf' => 'invoices/INV-2024-007.pdf',
                'paid_at' => '2024-04-11 12:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 8,
                'timesheet_id' => 8, // Jennifer Taylor, Week 1
                'company_id' => 15, // EarthTech Engineering
                'freelancer_id' => 9, // Jennifer Taylor
                'invoice_number' => 'INV-2024-008',
                'invoice_date' => '2024-02-22',
                'due_date' => '2024-03-23',
                'subtotal' => 3000.00,
                'tax_amount' => 150.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3150.00,
                'status' => 'Paid',
                'notes' => 'Payment for geotechnical investigation services',
                'invoice_pdf' => 'invoices/INV-2024-008.pdf',
                'paid_at' => '2024-02-25 16:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 9,
                'timesheet_id' => 9, // Robert Anderson, Week 1
                'company_id' => 16, // Geology Consultants
                'freelancer_id' => 8, // Robert Anderson
                'invoice_number' => 'INV-2024-009',
                'invoice_date' => '2024-01-08',
                'due_date' => '2024-02-07',
                'subtotal' => 4000.00,
                'tax_amount' => 200.00,
                'tax_percentage' => 5.00,
                'total_amount' => 4200.00,
                'status' => 'Paid',
                'notes' => 'Payment for petroleum geology assessment services',
                'invoice_pdf' => 'invoices/INV-2024-009.pdf',
                'paid_at' => '2024-01-11 14:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 10,
                'timesheet_id' => 10, // John Smith, REE Project
                'company_id' => 17, // Resource Exploration Group
                'freelancer_id' => 2, // John Smith
                'invoice_number' => 'INV-2024-010',
                'invoice_date' => '2024-03-22',
                'due_date' => '2024-04-21',
                'subtotal' => 3400.00,
                'tax_amount' => 170.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3570.00,
                'status' => 'Paid',
                'notes' => 'Payment for rare earth elements exploration services',
                'invoice_pdf' => 'invoices/INV-2024-010.pdf',
                'paid_at' => '2024-03-25 11:45:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pending invoices
            [
                'invoice_id' => 11,
                'timesheet_id' => 11, // John Smith, Pending
                'company_id' => 10, // Northern Mining Corp
                'freelancer_id' => 2, // John Smith
                'invoice_number' => 'INV-2024-011',
                'invoice_date' => '2024-02-05',
                'due_date' => '2024-03-06',
                'subtotal' => 3400.00,
                'tax_amount' => 170.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3570.00,
                'status' => 'Sent',
                'notes' => 'Payment for geological mapping and analysis services',
                'invoice_pdf' => 'invoices/INV-2024-011.pdf',
                'paid_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 12,
                'timesheet_id' => 12, // Sarah Jones, Pending
                'company_id' => 11, // GeoData Analytics
                'freelancer_id' => 3, // Sarah Jones
                'invoice_number' => 'INV-2024-012',
                'invoice_date' => '2024-02-22',
                'due_date' => '2024-03-23',
                'subtotal' => 3510.00,
                'tax_amount' => 175.50,
                'tax_percentage' => 5.00,
                'total_amount' => 3685.50,
                'status' => 'Sent',
                'notes' => 'Payment for geophysical data processing services',
                'invoice_pdf' => 'invoices/INV-2024-012.pdf',
                'paid_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invoice_id' => 13,
                'timesheet_id' => 13, // Michael Brown, Pending
                'company_id' => 12, // Exploration Corp International
                'freelancer_id' => 4, // Michael Brown
                'invoice_number' => 'INV-2024-013',
                'invoice_date' => '2024-03-15',
                'due_date' => '2024-04-14',
                'subtotal' => 3440.00,
                'tax_amount' => 172.00,
                'tax_percentage' => 5.00,
                'total_amount' => 3612.00,
                'status' => 'Sent',
                'notes' => 'Payment for geological engineering services',
                'invoice_pdf' => 'invoices/INV-2024-013.pdf',
                'paid_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('invoices')->insert($invoices);
    }
}
