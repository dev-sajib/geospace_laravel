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
        DB::table('dispute_status')->insert([
            [
                'status_id' => 1,
                'status_name' => 'Open',
                'status_description' => 'Dispute ticket is open and awaiting review',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 2,
                'status_name' => 'In Progress',
                'status_description' => 'Dispute is being investigated',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 3,
                'status_name' => 'Resolved',
                'status_description' => 'Dispute has been resolved',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
            [
                'status_id' => 4,
                'status_name' => 'Closed',
                'status_description' => 'Dispute ticket is closed',
                'is_active' => true,
                'created_at' => '2025-10-07 18:48:50',
            ],
        ]);
    }
}
