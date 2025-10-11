<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSkillsSeeder extends Seeder
{
    public function run(): void
    {
        $userSkills = [
            // John Smith (user_id: 2) - Senior Geologist
            ['user_id' => 2, 'skill_id' => 1, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Geological Mapping
            ['user_id' => 2, 'skill_id' => 2, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Core Logging
            ['user_id' => 2, 'skill_id' => 3, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Stratigraphy
            ['user_id' => 2, 'skill_id' => 4, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Mineralogy
            ['user_id' => 2, 'skill_id' => 21, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // ArcGIS
            ['user_id' => 2, 'skill_id' => 23, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Leapfrog
            ['user_id' => 2, 'skill_id' => 15, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Mine Planning
            ['user_id' => 2, 'skill_id' => 16, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Resource Estimation

            // Sarah Jones (user_id: 3) - Geophysicist
            ['user_id' => 3, 'skill_id' => 9, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Seismic Interpretation
            ['user_id' => 3, 'skill_id' => 10, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Gravity Surveying
            ['user_id' => 3, 'skill_id' => 11, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Magnetic Surveying
            ['user_id' => 3, 'skill_id' => 12, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Electrical Resistivity
            ['user_id' => 3, 'skill_id' => 13, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Ground Penetrating Radar
            ['user_id' => 3, 'skill_id' => 14, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Induced Polarization
            ['user_id' => 3, 'skill_id' => 27, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Python
            ['user_id' => 3, 'skill_id' => 28, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // R

            // Michael Brown (user_id: 4) - Geological Engineer
            ['user_id' => 4, 'skill_id' => 1, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Geological Mapping
            ['user_id' => 4, 'skill_id' => 6, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Structural Geology
            ['user_id' => 4, 'skill_id' => 7, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Geochemistry
            ['user_id' => 4, 'skill_id' => 15, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Mine Planning
            ['user_id' => 4, 'skill_id' => 16, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Resource Estimation
            ['user_id' => 4, 'skill_id' => 17, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Grade Control
            ['user_id' => 4, 'skill_id' => 18, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Blast Design
            ['user_id' => 4, 'skill_id' => 21, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // ArcGIS
            ['user_id' => 4, 'skill_id' => 23, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Leapfrog
            ['user_id' => 4, 'skill_id' => 24, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Surpac

            // Emily Davis (user_id: 5) - Mining Geologist
            ['user_id' => 5, 'skill_id' => 1, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Geological Mapping
            ['user_id' => 5, 'skill_id' => 2, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Core Logging
            ['user_id' => 5, 'skill_id' => 15, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Mine Planning
            ['user_id' => 5, 'skill_id' => 16, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Resource Estimation
            ['user_id' => 5, 'skill_id' => 17, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Grade Control
            ['user_id' => 5, 'skill_id' => 19, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Mine Safety
            ['user_id' => 5, 'skill_id' => 20, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Environmental Impact Assessment
            ['user_id' => 5, 'skill_id' => 23, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Leapfrog
            ['user_id' => 5, 'skill_id' => 24, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Surpac
            ['user_id' => 5, 'skill_id' => 25, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Datamine

            // David Wilson (user_id: 6) - Environmental Geologist
            ['user_id' => 6, 'skill_id' => 1, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Geological Mapping
            ['user_id' => 6, 'skill_id' => 3, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Stratigraphy
            ['user_id' => 6, 'skill_id' => 7, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Geochemistry
            ['user_id' => 6, 'skill_id' => 29, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Environmental Monitoring
            ['user_id' => 6, 'skill_id' => 30, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Water Quality Assessment
            ['user_id' => 6, 'skill_id' => 31, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Soil Contamination Assessment
            ['user_id' => 6, 'skill_id' => 32, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Remediation Planning
            ['user_id' => 6, 'skill_id' => 21, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // ArcGIS
            ['user_id' => 6, 'skill_id' => 22, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // QGIS

            // Lisa Martinez (user_id: 7) - Hydrogeologist
            ['user_id' => 7, 'skill_id' => 1, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Geological Mapping
            ['user_id' => 7, 'skill_id' => 3, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Stratigraphy
            ['user_id' => 7, 'skill_id' => 7, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Geochemistry
            ['user_id' => 7, 'skill_id' => 29, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Environmental Monitoring
            ['user_id' => 7, 'skill_id' => 30, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Water Quality Assessment
            ['user_id' => 7, 'skill_id' => 12, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Electrical Resistivity
            ['user_id' => 7, 'skill_id' => 13, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // Ground Penetrating Radar
            ['user_id' => 7, 'skill_id' => 21, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // ArcGIS
            ['user_id' => 7, 'skill_id' => 22, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // QGIS

            // Robert Anderson (user_id: 8) - Petroleum Geologist
            ['user_id' => 8, 'skill_id' => 1, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Geological Mapping
            ['user_id' => 8, 'skill_id' => 3, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Stratigraphy
            ['user_id' => 8, 'skill_id' => 9, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Seismic Interpretation
            ['user_id' => 8, 'skill_id' => 33, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Well Logging
            ['user_id' => 8, 'skill_id' => 34, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Reservoir Characterization
            ['user_id' => 8, 'skill_id' => 35, 'proficiency_level' => 'Expert', 'years_of_experience' => 12], // Seismic Data Processing
            ['user_id' => 8, 'skill_id' => 36, 'proficiency_level' => 'Expert', 'years_of_experience' => 15], // Petroleum Geology
            ['user_id' => 8, 'skill_id' => 21, 'proficiency_level' => 'Expert', 'years_of_experience' => 10], // ArcGIS
            ['user_id' => 8, 'skill_id' => 27, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Python

            // Jennifer Taylor (user_id: 9) - Engineering Geologist
            ['user_id' => 9, 'skill_id' => 1, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Geological Mapping
            ['user_id' => 9, 'skill_id' => 6, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Structural Geology
            ['user_id' => 9, 'skill_id' => 7, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Geochemistry
            ['user_id' => 9, 'skill_id' => 15, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Mine Planning
            ['user_id' => 9, 'skill_id' => 16, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Resource Estimation
            ['user_id' => 9, 'skill_id' => 20, 'proficiency_level' => 'Expert', 'years_of_experience' => 8], // Environmental Impact Assessment
            ['user_id' => 9, 'skill_id' => 21, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // ArcGIS
            ['user_id' => 9, 'skill_id' => 23, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Leapfrog
            ['user_id' => 9, 'skill_id' => 24, 'proficiency_level' => 'Expert', 'years_of_experience' => 6], // Surpac
        ];

        foreach ($userSkills as $userSkill) {
            $userSkill['created_at'] = now();
            $userSkill['updated_at'] = now();
        }

        DB::table('user_skills')->insert($userSkills);
    }
}
