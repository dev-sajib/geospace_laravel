<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropdownSeeder extends Seeder
{
    public function run(): void
    {
        // Dropdown categories
        $categories = [
            ['category_id' => 1, 'category_name' => 'Countries', 'category_description' => 'List of countries', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 2, 'category_name' => 'Provinces', 'category_description' => 'Canadian provinces and territories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 3, 'category_name' => 'CompanySizes', 'category_description' => 'Company size categories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 4, 'category_name' => 'Industries', 'category_description' => 'Industry categories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 5, 'category_name' => 'ProjectTypes', 'category_description' => 'Project type categories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['category_id' => 6, 'category_name' => 'ExperienceLevels', 'category_description' => 'Experience level categories', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('dropdown_categories')->insert($categories);

        // Dropdown values
        $values = [
            // Countries
            ['value_id' => 1, 'category_id' => 1, 'value_name' => 'Canada', 'value_code' => 'CA', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 2, 'category_id' => 1, 'value_name' => 'United States', 'value_code' => 'US', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 3, 'category_id' => 1, 'value_name' => 'United Kingdom', 'value_code' => 'UK', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 4, 'category_id' => 1, 'value_name' => 'Australia', 'value_code' => 'AU', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Canadian Provinces
            ['value_id' => 5, 'category_id' => 2, 'value_name' => 'Ontario', 'value_code' => 'ON', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 6, 'category_id' => 2, 'value_name' => 'British Columbia', 'value_code' => 'BC', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 7, 'category_id' => 2, 'value_name' => 'Alberta', 'value_code' => 'AB', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 8, 'category_id' => 2, 'value_name' => 'Quebec', 'value_code' => 'QC', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 9, 'category_id' => 2, 'value_name' => 'Manitoba', 'value_code' => 'MB', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 10, 'category_id' => 2, 'value_name' => 'Saskatchewan', 'value_code' => 'SK', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 11, 'category_id' => 2, 'value_name' => 'Nova Scotia', 'value_code' => 'NS', 'sort_order' => 7, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 12, 'category_id' => 2, 'value_name' => 'New Brunswick', 'value_code' => 'NB', 'sort_order' => 8, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 13, 'category_id' => 2, 'value_name' => 'Newfoundland and Labrador', 'value_code' => 'NL', 'sort_order' => 9, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 14, 'category_id' => 2, 'value_name' => 'Prince Edward Island', 'value_code' => 'PE', 'sort_order' => 10, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 15, 'category_id' => 2, 'value_name' => 'Northwest Territories', 'value_code' => 'NT', 'sort_order' => 11, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 16, 'category_id' => 2, 'value_name' => 'Yukon', 'value_code' => 'YT', 'sort_order' => 12, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 17, 'category_id' => 2, 'value_name' => 'Nunavut', 'value_code' => 'NU', 'sort_order' => 13, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Company Sizes
            ['value_id' => 18, 'category_id' => 3, 'value_name' => '1-10 employees', 'value_code' => '1-10', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 19, 'category_id' => 3, 'value_name' => '11-50 employees', 'value_code' => '11-50', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 20, 'category_id' => 3, 'value_name' => '51-200 employees', 'value_code' => '51-200', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 21, 'category_id' => 3, 'value_name' => '201-500 employees', 'value_code' => '201-500', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 22, 'category_id' => 3, 'value_name' => '500+ employees', 'value_code' => '500+', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Industries
            ['value_id' => 23, 'category_id' => 4, 'value_name' => 'Mining', 'value_code' => 'MINING', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 24, 'category_id' => 4, 'value_name' => 'Oil & Gas', 'value_code' => 'OIL_GAS', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 25, 'category_id' => 4, 'value_name' => 'Environmental', 'value_code' => 'ENVIRONMENTAL', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 26, 'category_id' => 4, 'value_name' => 'Geotechnical', 'value_code' => 'GEOTECHNICAL', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 27, 'category_id' => 4, 'value_name' => 'Consulting', 'value_code' => 'CONSULTING', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 28, 'category_id' => 4, 'value_name' => 'Technology', 'value_code' => 'TECHNOLOGY', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Project Types
            ['value_id' => 29, 'category_id' => 5, 'value_name' => 'Mineral Exploration', 'value_code' => 'MINERAL_EXPLORATION', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 30, 'category_id' => 5, 'value_name' => 'Environmental Assessment', 'value_code' => 'ENV_ASSESSMENT', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 31, 'category_id' => 5, 'value_name' => 'Geotechnical Investigation', 'value_code' => 'GEOTECH_INVESTIGATION', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 32, 'category_id' => 5, 'value_name' => 'Petroleum Geology', 'value_code' => 'PETROLEUM_GEOLOGY', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 33, 'category_id' => 5, 'value_name' => 'Geospatial Analysis', 'value_code' => 'GEOSPATIAL_ANALYSIS', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],

            // Experience Levels
            ['value_id' => 34, 'category_id' => 6, 'value_name' => 'Entry Level', 'value_code' => 'ENTRY', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 35, 'category_id' => 6, 'value_name' => 'Intermediate', 'value_code' => 'INTERMEDIATE', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['value_id' => 36, 'category_id' => 6, 'value_name' => 'Expert', 'value_code' => 'EXPERT', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('dropdown_values')->insert($values);
    }
}
