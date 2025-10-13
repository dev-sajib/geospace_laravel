<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contracts')->insert([
            [
                'contract_id' => 1,
                'project_id' => 1,
                'freelancer_id' => 2,
                'company_id' => 1,
                'contract_title' => 'Gold Survey Contract',
                'contract_description' => 'Geological mapping and sampling',
                'hourly_rate' => 85.00,
                'total_amount' => 65000.00,
                'start_date' => '2025-09-01',
                'end_date' => '2025-11-30',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'contract_id' => 2,
                'project_id' => 2,
                'freelancer_id' => 3,
                'company_id' => 2,
                'contract_title' => 'GIS Analysis Contract',
                'contract_description' => 'Data analysis and visualization',
                'hourly_rate' => 90.00,
                'total_amount' => 45000.00,
                'start_date' => '2025-09-15',
                'end_date' => '2025-11-15',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'contract_id' => 3,
                'project_id' => 3,
                'freelancer_id' => 4,
                'company_id' => 3,
                'contract_title' => 'Copper Exploration Contract',
                'contract_description' => 'Exploration and sampling',
                'hourly_rate' => 80.00,
                'total_amount' => 95000.00,
                'start_date' => '2025-09-08',
                'end_date' => '2025-12-31',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'contract_id' => 4,
                'project_id' => 4,
                'freelancer_id' => 5,
                'company_id' => 4,
                'contract_title' => 'Environmental Assessment Contract',
                'contract_description' => 'Impact assessment and reporting',
                'hourly_rate' => 85.00,
                'total_amount' => 32000.00,
                'start_date' => '2025-09-01',
                'end_date' => '2025-10-15',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'contract_id' => 5,
                'project_id' => 5,
                'freelancer_id' => 6,
                'company_id' => 5,
                'contract_title' => 'Diamond Prospecting Contract',
                'contract_description' => 'Prospecting and analysis',
                'hourly_rate' => 90.00,
                'total_amount' => 75000.00,
                'start_date' => '2025-09-22',
                'end_date' => '2025-12-01',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'contract_id' => 6,
                'project_id' => 6,
                'freelancer_id' => 7,
                'company_id' => 6,
                'contract_title' => 'Geotechnical Investigation Contract',
                'contract_description' => 'Site investigation and testing',
                'hourly_rate' => 95.00,
                'total_amount' => 48000.00,
                'start_date' => '2025-08-25',
                'end_date' => '2025-10-20',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'contract_id' => 7,
                'project_id' => 7,
                'freelancer_id' => 8,
                'company_id' => 7,
                'contract_title' => 'Basin Analysis Contract',
                'contract_description' => 'Petroleum basin analysis',
                'hourly_rate' => 100.00,
                'total_amount' => 85000.00,
                'start_date' => '2025-08-18',
                'end_date' => '2025-11-18',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'contract_id' => 8,
                'project_id' => 8,
                'freelancer_id' => 9,
                'company_id' => 8,
                'contract_title' => 'Resource Estimation Contract',
                'contract_description' => 'Mineral resource estimation',
                'hourly_rate' => 75.00,
                'total_amount' => 55000.00,
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-10',
                'status' => 'Active',
                'payment_terms' => null,
                'milestones' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ]
        ]);
    }
}
