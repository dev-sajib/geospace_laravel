<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractsSeeder extends Seeder
{
    public function run(): void
    {
        $contracts = [
            [
                'contract_id' => 1,
                'project_id' => 1, // Northern Gold Exploration Project
                'freelancer_id' => 2, // John Smith
                'company_id' => 10, // Northern Mining Corp
                'contract_title' => 'Senior Geologist - Gold Exploration',
                'contract_description' => 'Lead geological mapping and core logging activities for the Northern Gold Exploration Project.',
                'start_date' => '2024-01-15',
                'end_date' => '2024-12-31',
                'contract_value' => 120000.00,
                'payment_terms' => 'Fixed Price',
                'status' => 'Active',
                'terms_and_conditions' => 'Contract includes field work, data analysis, and report preparation. Payment upon completion of milestones.',
                'contract_document' => 'contracts/northern_gold_john_smith.pdf',
                'signed_at' => '2024-01-10 10:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 2,
                'project_id' => 2, // Geospatial Data Analysis
                'freelancer_id' => 3, // Sarah Jones
                'company_id' => 11, // GeoData Analytics
                'contract_title' => 'Geophysicist - Data Analysis',
                'contract_description' => 'Conduct geospatial analysis and modeling for mining operations optimization.',
                'start_date' => '2024-02-01',
                'end_date' => '2024-08-31',
                'contract_value' => 75000.00,
                'payment_terms' => 'Hourly',
                'status' => 'Active',
                'terms_and_conditions' => 'Hourly rate of $90/hour. Weekly timesheet submission required.',
                'contract_document' => 'contracts/geodata_sarah_jones.pdf',
                'signed_at' => '2024-01-25 14:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 3,
                'project_id' => 3, // Copper-Nickel Exploration
                'freelancer_id' => 4, // Michael Brown
                'company_id' => 12, // Exploration Corp International
                'contract_title' => 'Geological Engineer - Copper Exploration',
                'contract_description' => 'Lead geological engineering activities for copper-nickel exploration in Northern Quebec.',
                'start_date' => '2024-03-01',
                'end_date' => '2025-02-28',
                'contract_value' => 180000.00,
                'payment_terms' => 'Milestone-based',
                'status' => 'Active',
                'terms_and_conditions' => 'Payment based on milestone completion: 25% at start, 25% at 3 months, 25% at 6 months, 25% at completion.',
                'contract_document' => 'contracts/exploration_corp_michael_brown.pdf',
                'signed_at' => '2024-02-20 09:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 4,
                'project_id' => 4, // Environmental Impact Assessment
                'freelancer_id' => 6, // David Wilson
                'company_id' => 13, // Geo Services Ltd
                'contract_title' => 'Environmental Geologist - EIA',
                'contract_description' => 'Conduct comprehensive environmental impact assessment for mining project.',
                'start_date' => '2024-01-01',
                'end_date' => '2024-06-30',
                'contract_value' => 85000.00,
                'payment_terms' => 'Fixed Price',
                'status' => 'Active',
                'terms_and_conditions' => 'Fixed price contract with monthly progress payments.',
                'contract_document' => 'contracts/geo_services_david_wilson.pdf',
                'signed_at' => '2023-12-15 16:45:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 5,
                'project_id' => 5, // Diamond Exploration
                'freelancer_id' => 5, // Emily Davis
                'company_id' => 14, // Mineral Solutions Inc
                'contract_title' => 'Mining Geologist - Diamond Exploration',
                'contract_description' => 'Lead diamond exploration activities in Northern Canada.',
                'start_date' => '2024-04-01',
                'end_date' => '2025-03-31',
                'contract_value' => 150000.00,
                'payment_terms' => 'Fixed Price',
                'status' => 'Active',
                'terms_and_conditions' => 'Contract includes field work, core logging, and geological mapping.',
                'contract_document' => 'contracts/mineral_solutions_emily_davis.pdf',
                'signed_at' => '2024-03-15 11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 6,
                'project_id' => 6, // Geotechnical Investigation
                'freelancer_id' => 9, // Jennifer Taylor
                'company_id' => 15, // EarthTech Engineering
                'contract_title' => 'Engineering Geologist - Geotechnical',
                'contract_description' => 'Conduct geotechnical investigation for infrastructure project.',
                'start_date' => '2024-02-15',
                'end_date' => '2024-09-30',
                'contract_value' => 95000.00,
                'payment_terms' => 'Hourly',
                'status' => 'Active',
                'terms_and_conditions' => 'Hourly rate of $75/hour. Bi-weekly timesheet submission.',
                'contract_document' => 'contracts/earthtech_jennifer_taylor.pdf',
                'signed_at' => '2024-02-01 13:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 7,
                'project_id' => 7, // Petroleum Geology Assessment
                'freelancer_id' => 8, // Robert Anderson
                'company_id' => 16, // Geology Consultants
                'contract_title' => 'Petroleum Geologist - Oil Sands',
                'contract_description' => 'Lead petroleum geology assessment for oil sands development.',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'contract_value' => 200000.00,
                'payment_terms' => 'Milestone-based',
                'status' => 'Active',
                'terms_and_conditions' => 'Payment based on quarterly milestones and deliverables.',
                'contract_document' => 'contracts/geology_consultants_robert_anderson.pdf',
                'signed_at' => '2023-12-20 10:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 8,
                'project_id' => 8, // Rare Earth Elements Exploration
                'freelancer_id' => 2, // John Smith
                'company_id' => 17, // Resource Exploration Group
                'contract_title' => 'Senior Geologist - REE Exploration',
                'contract_description' => 'Lead rare earth elements exploration project in the Canadian Shield.',
                'start_date' => '2024-03-15',
                'end_date' => '2025-03-14',
                'contract_value' => 160000.00,
                'payment_terms' => 'Fixed Price',
                'status' => 'Active',
                'terms_and_conditions' => 'Contract includes geological mapping, core logging, and resource estimation.',
                'contract_document' => 'contracts/resource_group_john_smith.pdf',
                'signed_at' => '2024-03-01 14:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 9,
                'project_id' => 9, // Mine Safety and Environmental Compliance Audit
                'freelancer_id' => 5, // Emily Davis
                'company_id' => 10, // Northern Mining Corp
                'contract_title' => 'Mining Geologist - Safety Audit',
                'contract_description' => 'Conduct mine safety and environmental compliance audit.',
                'start_date' => '2024-05-01',
                'end_date' => '2024-08-31',
                'contract_value' => 45000.00,
                'payment_terms' => 'Fixed Price',
                'status' => 'Active',
                'terms_and_conditions' => 'Fixed price contract with monthly progress payments.',
                'contract_document' => 'contracts/northern_mining_emily_davis_audit.pdf',
                'signed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contract_id' => 10,
                'project_id' => 10, // Machine Learning for Mineral Exploration
                'freelancer_id' => 3, // Sarah Jones
                'company_id' => 11, // GeoData Analytics
                'contract_title' => 'Geophysicist - ML Development',
                'contract_description' => 'Develop machine learning algorithms for automated mineral exploration.',
                'start_date' => '2024-06-01',
                'end_date' => '2024-12-31',
                'contract_value' => 80000.00,
                'payment_terms' => 'Hourly',
                'status' => 'Active',
                'terms_and_conditions' => 'Hourly rate of $90/hour. Weekly timesheet submission required.',
                'contract_document' => 'contracts/geodata_sarah_jones_ml.pdf',
                'signed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('contracts')->insert($contracts);
    }
}
