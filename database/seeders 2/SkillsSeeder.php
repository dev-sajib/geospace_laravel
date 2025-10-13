<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Geological Skills
            ['skill_name' => 'Geological Mapping', 'skill_category' => 'Field Work', 'description' => 'Creating detailed geological maps and cross-sections'],
            ['skill_name' => 'Core Logging', 'skill_category' => 'Field Work', 'description' => 'Detailed logging of drill core samples'],
            ['skill_name' => 'Stratigraphy', 'skill_category' => 'Geology', 'description' => 'Study of rock layers and their relationships'],
            ['skill_name' => 'Mineralogy', 'skill_category' => 'Geology', 'description' => 'Identification and analysis of minerals'],
            ['skill_name' => 'Petrology', 'skill_category' => 'Geology', 'description' => 'Study of rocks and their formation'],
            ['skill_name' => 'Structural Geology', 'skill_category' => 'Geology', 'description' => 'Analysis of rock deformation and structures'],
            ['skill_name' => 'Geochemistry', 'skill_category' => 'Geology', 'description' => 'Chemical analysis of rocks and minerals'],
            ['skill_name' => 'Geochronology', 'skill_category' => 'Geology', 'description' => 'Dating of geological materials'],
            
            // Geophysical Skills
            ['skill_name' => 'Seismic Interpretation', 'skill_category' => 'Geophysics', 'description' => 'Analysis of seismic data for subsurface structures'],
            ['skill_name' => 'Gravity Surveying', 'skill_category' => 'Geophysics', 'description' => 'Gravity measurements for subsurface mapping'],
            ['skill_name' => 'Magnetic Surveying', 'skill_category' => 'Geophysics', 'description' => 'Magnetic field measurements'],
            ['skill_name' => 'Electrical Resistivity', 'skill_category' => 'Geophysics', 'description' => 'Electrical resistivity surveying'],
            ['skill_name' => 'Ground Penetrating Radar', 'skill_category' => 'Geophysics', 'description' => 'GPR data collection and interpretation'],
            ['skill_name' => 'Induced Polarization', 'skill_category' => 'Geophysics', 'description' => 'IP surveying for mineral exploration'],
            
            // Mining Skills
            ['skill_name' => 'Mine Planning', 'skill_category' => 'Mining', 'description' => 'Strategic and operational mine planning'],
            ['skill_name' => 'Resource Estimation', 'skill_category' => 'Mining', 'description' => 'Estimation of mineral resources and reserves'],
            ['skill_name' => 'Grade Control', 'skill_category' => 'Mining', 'description' => 'Ore grade monitoring and control'],
            ['skill_name' => 'Blast Design', 'skill_category' => 'Mining', 'description' => 'Design of blasting operations'],
            ['skill_name' => 'Mine Safety', 'skill_category' => 'Mining', 'description' => 'Mine safety protocols and compliance'],
            ['skill_name' => 'Environmental Impact Assessment', 'skill_category' => 'Mining', 'description' => 'EIA for mining operations'],
            
            // Software Skills
            ['skill_name' => 'ArcGIS', 'skill_category' => 'Software', 'description' => 'Geographic Information Systems'],
            ['skill_name' => 'QGIS', 'skill_category' => 'Software', 'description' => 'Open source GIS software'],
            ['skill_name' => 'Leapfrog', 'skill_category' => 'Software', 'description' => '3D geological modeling software'],
            ['skill_name' => 'Surpac', 'skill_category' => 'Software', 'description' => 'Mine planning and geological modeling'],
            ['skill_name' => 'Datamine', 'skill_category' => 'Software', 'description' => 'Mining software suite'],
            ['skill_name' => 'AutoCAD', 'skill_category' => 'Software', 'description' => 'Computer-aided design software'],
            ['skill_name' => 'Python', 'skill_category' => 'Programming', 'description' => 'Python programming for geoscience'],
            ['skill_name' => 'R', 'skill_category' => 'Programming', 'description' => 'Statistical programming language'],
            ['skill_name' => 'MATLAB', 'skill_category' => 'Programming', 'description' => 'Mathematical computing software'],
            
            // Environmental Skills
            ['skill_name' => 'Environmental Monitoring', 'skill_category' => 'Environmental', 'description' => 'Environmental impact monitoring'],
            ['skill_name' => 'Water Quality Assessment', 'skill_category' => 'Environmental', 'description' => 'Water quality testing and analysis'],
            ['skill_name' => 'Soil Contamination Assessment', 'skill_category' => 'Environmental', 'description' => 'Soil contamination evaluation'],
            ['skill_name' => 'Remediation Planning', 'skill_category' => 'Environmental', 'description' => 'Environmental remediation strategies'],
            
            // Petroleum Skills
            ['skill_name' => 'Well Logging', 'skill_category' => 'Petroleum', 'description' => 'Analysis of well log data'],
            ['skill_name' => 'Reservoir Characterization', 'skill_category' => 'Petroleum', 'description' => 'Reservoir properties analysis'],
            ['skill_name' => 'Seismic Data Processing', 'skill_category' => 'Petroleum', 'description' => 'Processing of seismic data'],
            ['skill_name' => 'Petroleum Geology', 'skill_category' => 'Petroleum', 'description' => 'Geology of petroleum systems'],
        ];

        foreach ($skills as $skill) {
            $skill['created_at'] = now();
            $skill['updated_at'] = now();
        }

        DB::table('skills')->insert($skills);
    }
}
