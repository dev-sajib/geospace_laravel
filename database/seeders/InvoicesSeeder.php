<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoicesSeeder extends Seeder
{
    public function run()
    {
        DB::table('invoices')->insert([
            [
                'invoice_id' => 1,
                'timesheet_id' => 4,
                'contract_id' => 4,
                'company_id' => 4,
                'freelancer_id' => 5,
                'invoice_number' => 'INV-2025-001',
                'invoice_date' => '2025-09-16',
                'total_hours' => 38.00,
                'hourly_rate' => 85.00,
                'subtotal' => 3230.00,
                'tax_amount' => 484.50,
                'total_amount' => 3714.50,
                'currency' => 'CAD',
                'status' => 'Generated',
                'due_date' => '2025-09-30',
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
        ]);
    }
}
