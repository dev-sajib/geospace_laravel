<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentRequestsSeeder extends Seeder
{
    public function run()
    {
        DB::table('payment_requests')->insert([
            [
                'request_id' => 1,
                'timesheet_id' => 4,
                'freelancer_id' => 5,
                'invoice_id' => 1,
                'amount' => 3714.50,
                'status' => 'Pending',
                'created_at' => '2025-09-20 10:00:00',
                'updated_at' => '2025-09-20 10:00:00',
            ],
        ]);
    }
}
