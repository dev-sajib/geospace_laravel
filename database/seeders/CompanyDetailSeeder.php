<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyDetailSeeder extends Seeder
{
    public function run(): void
    {
        $companyDetails = [
            [
                'company_id' => 1,
                'user_id' => 10,
                'company_name' => 'Northern Mining Corp',
                'company_type' => 'Mining',
                'industry' => 'Exploration',
                'company_size' => '201-500',
                'website' => 'www.northernmining.com',
                'headquarters' => 'Toronto, ON',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'user_id' => 11,
                'company_name' => 'GeoData Analytics',
                'company_type' => 'Technology',
                'industry' => 'Geospatial',
                'company_size' => '51-200',
                'website' => 'www.geodata.com',
                'headquarters' => 'Vancouver, BC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 3,
                'user_id' => 12,
                'company_name' => 'Exploration Corp International',
                'company_type' => 'Mining',
                'industry' => 'Mineral Exploration',
                'company_size' => '500+',
                'website' => 'www.explorationcorp.com',
                'headquarters' => 'Calgary, AB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 4,
                'user_id' => 13,
                'company_name' => 'Geo Services Ltd',
                'company_type' => 'Consulting',
                'industry' => 'Geological Services',
                'company_size' => '11-50',
                'website' => 'www.geoservices.com',
                'headquarters' => 'Montreal, QC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 5,
                'user_id' => 14,
                'company_name' => 'Mineral Solutions Inc',
                'company_type' => 'Mining',
                'industry' => 'Resource Development',
                'company_size' => '51-200',
                'website' => 'www.mineralsolutions.com',
                'headquarters' => 'Toronto, ON',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 6,
                'user_id' => 15,
                'company_name' => 'EarthTech Engineering',
                'company_type' => 'Engineering',
                'industry' => 'Geotechnical',
                'company_size' => '201-500',
                'website' => 'www.earthtech.com',
                'headquarters' => 'Vancouver, BC',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 7,
                'user_id' => 16,
                'company_name' => 'Geology Consultants',
                'company_type' => 'Consulting',
                'industry' => 'Geological Consulting',
                'company_size' => '11-50',
                'website' => 'www.geologyconsult.com',
                'headquarters' => 'Calgary, AB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 8,
                'user_id' => 17,
                'company_name' => 'Resource Exploration Group',
                'company_type' => 'Mining',
                'industry' => 'Exploration',
                'company_size' => '51-200',
                'website' => 'www.resourcegroup.com',
                'headquarters' => 'Toronto, ON',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('company_details')->insert($companyDetails);
    }
}
