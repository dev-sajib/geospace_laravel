<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimesheetDaysSeeder extends Seeder
{
    public function run()
    {
        DB::table('timesheet_days')->insert([
            [
                'day_id' => 22,
                'timesheet_id' => 4,
                'work_date' => '2025-09-08',
                'day_name' => 'Monday',
                'day_number' => 1,
                'hours_worked' => 8.00,
                'task_description' => 'Work on GeoSpace project frontend integration',
                'created_at' => '2025-09-08 10:00:00',
                'updated_at' => '2025-09-08 10:00:00',
            ],
        ]);
    }
}
