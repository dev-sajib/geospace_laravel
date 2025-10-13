<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'project_id' => 1,
                'company_id' => 1,
                'project_title' => 'Northern Ontario Gold Survey',
                'project_description' => 'Comprehensive geological survey for gold exploration',
                'project_type' => 'Geological Mapping',
                'budget_min' => 50000.00,
                'budget_max' => 75000.00,
                'currency' => 'CAD',
                'duration_weeks' => 12,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 0,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'project_id' => 2,
                'company_id' => 2,
                'project_title' => 'GIS Data Analysis Project',
                'project_description' => 'Analyze geological data using GIS technology',
                'project_type' => 'Data Analysis',
                'budget_min' => 30000.00,
                'budget_max' => 45000.00,
                'currency' => 'CAD',
                'duration_weeks' => 8,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 1,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'project_id' => 3,
                'company_id' => 3,
                'project_title' => 'BC Copper Exploration',
                'project_description' => 'Copper deposit exploration in British Columbia',
                'project_type' => 'Mineral Exploration',
                'budget_min' => 80000.00,
                'budget_max' => 120000.00,
                'currency' => 'CAD',
                'duration_weeks' => 16,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 0,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'project_id' => 4,
                'company_id' => 4,
                'project_title' => 'Environmental Impact Assessment',
                'project_description' => 'Geological assessment for environmental project',
                'project_type' => 'Environmental',
                'budget_min' => 25000.00,
                'budget_max' => 35000.00,
                'currency' => 'CAD',
                'duration_weeks' => 6,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 1,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'project_id' => 5,
                'company_id' => 5,
                'project_title' => 'Diamond Prospecting Study',
                'project_description' => 'Diamond prospecting in northern territories',
                'project_type' => 'Exploration',
                'budget_min' => 60000.00,
                'budget_max' => 90000.00,
                'currency' => 'CAD',
                'duration_weeks' => 10,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 0,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'project_id' => 6,
                'company_id' => 6,
                'project_title' => 'Geotechnical Site Investigation',
                'project_description' => 'Site investigation for infrastructure project',
                'project_type' => 'Geotechnical',
                'budget_min' => 40000.00,
                'budget_max' => 55000.00,
                'currency' => 'CAD',
                'duration_weeks' => 8,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 0,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'project_id' => 7,
                'company_id' => 7,
                'project_title' => 'Oil and Gas Basin Analysis',
                'project_description' => 'Basin analysis for petroleum exploration',
                'project_type' => 'Petroleum',
                'budget_min' => 70000.00,
                'budget_max' => 100000.00,
                'currency' => 'CAD',
                'duration_weeks' => 12,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 1,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ],
            [
                'project_id' => 8,
                'company_id' => 8,
                'project_title' => 'Mineral Resource Estimation',
                'project_description' => 'Resource estimation for mining project',
                'project_type' => 'Mining',
                'budget_min' => 45000.00,
                'budget_max' => 65000.00,
                'currency' => 'CAD',
                'duration_weeks' => 10,
                'status' => 'In Progress',
                'skills_required' => null,
                'location' => null,
                'is_remote' => 0,
                'deadline' => null,
                'created_at' => '2025-10-07 18:48:50',
                'updated_at' => '2025-10-07 18:48:50'
            ]
        ]);
    }
}
