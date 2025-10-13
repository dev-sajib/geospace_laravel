<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimesheetDayCommentsSeeder extends Seeder
{
    public function run()
    {
        DB::table('timesheet_day_comments')->insert([
            [
                'comment_id' => 1,
                'day_id' => 22,
                'user_id' => 2,
                'comment_text' => 'Nice progress on UI tasks. Keep consistency across components.',
                'created_at' => '2025-09-09 12:00:00',
                'updated_at' => '2025-09-09 12:00:00',
            ],
        ]);
    }
}
