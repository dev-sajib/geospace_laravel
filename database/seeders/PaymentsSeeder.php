<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        DB::table('payments')->insert([
            [
                'payment_id' => 1,
                'invoice_id' => 1,
                'timesheet_id' => 4,
                'payment_request_id' => 1,
                'payment_type' => 'Company_to_Platform',
                'amount' => 3714.50,
                'currency' => 'CAD',
                'status' => 'Completed',
                'transaction_id' => 'TXN-COMP-20250930-001',
                'payment_method' => 'Bank Transfer',
                'payment_date' => '2025-09-30 12:00:00',
                'verified_by' => 1,
                'verified_at' => '2025-09-30 14:00:00',
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
        ]);
    }
}
