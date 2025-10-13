<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimesheetsSeeder extends Seeder
{
    public function run()
    {
        DB::table('timesheets')->insert([
            [
                'timesheet_id' => 4,
                'contract_id' => 4,
                'freelancer_id' => 5,
                'start_date' => '2025-09-08',
                'end_date' => '2025-09-14',
                'total_hours' => 38.00,
                'status_display_name' => 'Approved',
                'submitted_at' => '2025-09-15 03:00:00',
                'reviewed_at' => '2025-09-16 03:00:00',
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50',
            ],
        ]);
    }
}
