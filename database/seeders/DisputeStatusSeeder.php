<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisputeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dispute_status')->insertOrIgnore([
            [
                'status_id' => 1,
                'status_name' => 'Open',
                'status_description' => 'Dispute has been opened and is awaiting review',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 2,
                'status_name' => 'In Review',
                'status_description' => 'Dispute is being reviewed by support team',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 3,
                'status_name' => 'Pending Response',
                'status_description' => 'Waiting for response from one of the parties',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 4,
                'status_name' => 'Resolved',
                'status_description' => 'Dispute has been resolved successfully',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 5,
                'status_name' => 'Closed',
                'status_description' => 'Dispute has been closed',
                'is_active' => 1,
                'created_at' => now(),
            ],
            [
                'status_id' => 6,
                'status_name' => 'Escalated',
                'status_description' => 'Dispute has been escalated to higher authority',
                'is_active' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
