<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimesheetsSeeder extends Seeder
{
    public function run(): void
    {
        // First, let's create some timesheet statuses if they don't exist
        $timesheetStatuses = [
            ['status_id' => 1, 'status_name' => 'Draft', 'status_description' => 'Timesheet is being prepared', 'status_color' => '#6c757d', 'is_active' => true, 'created_at' => now()],
            ['status_id' => 2, 'status_name' => 'Submitted', 'status_description' => 'Timesheet submitted for review', 'status_color' => '#007bff', 'is_active' => true, 'created_at' => now()],
            ['status_id' => 3, 'status_name' => 'Under Review', 'status_description' => 'Timesheet is being reviewed', 'status_color' => '#ffc107', 'is_active' => true, 'created_at' => now()],
            ['status_id' => 4, 'status_name' => 'Approved', 'status_description' => 'Timesheet approved by company', 'status_color' => '#28a745', 'is_active' => true, 'created_at' => now()],
            ['status_id' => 5, 'status_name' => 'Rejected', 'status_description' => 'Timesheet rejected by company', 'status_color' => '#dc3545', 'is_active' => true, 'created_at' => now()],
            ['status_id' => 6, 'status_name' => 'Paid', 'status_description' => 'Payment processed for timesheet', 'status_color' => '#17a2b8', 'is_active' => true, 'created_at' => now()],
        ];

        DB::table('timesheet_status')->insertOrIgnore($timesheetStatuses);

        // Now create some sample timesheets
        $timesheets = [
            [
                'timesheet_id' => 1,
                'freelancer_id' => 2, // John Smith
                'company_id' => 10, // Northern Mining Corp
                'project_id' => 1, // Northern Gold Exploration Project
                'contract_id' => 1,
                'start_date' => '2025-01-15',
                'end_date' => '2025-01-21',
                'total_hours' => 40.0,
                'hourly_rate' => 85.00,
                'total_amount' => 3400.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-01-22 09:00:00',
                'reviewed_at' => '2025-01-23 14:30:00',
                'reviewed_by' => 10, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 2,
                'freelancer_id' => 2, // John Smith
                'company_id' => 10, // Northern Mining Corp
                'project_id' => 1, // Northern Gold Exploration Project
                'contract_id' => 1,
                'start_date' => '2025-01-22',
                'end_date' => '2025-01-28',
                'total_hours' => 42.0,
                'hourly_rate' => 85.00,
                'total_amount' => 3570.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-01-29 09:00:00',
                'reviewed_at' => '2025-01-30 16:45:00',
                'reviewed_by' => 10, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 3,
                'freelancer_id' => 3, // Sarah Jones
                'company_id' => 11, // GeoData Analytics
                'project_id' => 2, // Geospatial Data Analysis
                'contract_id' => 2,
                'start_date' => '2025-02-01',
                'end_date' => '2025-02-07',
                'total_hours' => 38.5,
                'hourly_rate' => 90.00,
                'total_amount' => 3465.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-02-08 09:00:00',
                'reviewed_at' => '2025-02-09 11:20:00',
                'reviewed_by' => 11, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 4,
                'freelancer_id' => 3, // Sarah Jones
                'company_id' => 11, // GeoData Analytics
                'project_id' => 2, // Geospatial Data Analysis
                'contract_id' => 2,
                'start_date' => '2025-02-08',
                'end_date' => '2025-02-14',
                'total_hours' => 40.0,
                'hourly_rate' => 90.00,
                'total_amount' => 3600.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-02-15 09:00:00',
                'reviewed_at' => '2025-02-16 14:15:00',
                'reviewed_by' => 11, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 5,
                'freelancer_id' => 4, // Michael Brown
                'company_id' => 12, // Exploration Corp International
                'project_id' => 3, // Copper-Nickel Exploration
                'contract_id' => 3,
                'start_date' => '2025-03-01',
                'end_date' => '2025-03-07',
                'total_hours' => 45.0,
                'hourly_rate' => 80.00,
                'total_amount' => 3600.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-03-08 09:00:00',
                'reviewed_at' => '2025-03-09 10:30:00',
                'reviewed_by' => 12, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 6,
                'freelancer_id' => 6, // David Wilson
                'company_id' => 13, // Geo Services Ltd
                'project_id' => 4, // Environmental Impact Assessment
                'contract_id' => 4,
                'start_date' => '2025-01-01',
                'end_date' => '2025-01-07',
                'total_hours' => 40.0,
                'hourly_rate' => 90.00,
                'total_amount' => 3600.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-01-08 09:00:00',
                'reviewed_at' => '2025-01-09 15:45:00',
                'reviewed_by' => 13, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 7,
                'freelancer_id' => 5, // Emily Davis
                'company_id' => 14, // Mineral Solutions Inc
                'project_id' => 5, // Diamond Exploration
                'contract_id' => 5,
                'start_date' => '2025-04-01',
                'end_date' => '2025-04-07',
                'total_hours' => 42.0,
                'hourly_rate' => 85.00,
                'total_amount' => 3570.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-04-08 09:00:00',
                'reviewed_at' => '2025-04-09 12:00:00',
                'reviewed_by' => 14, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 8,
                'freelancer_id' => 9, // Jennifer Taylor
                'company_id' => 15, // EarthTech Engineering
                'project_id' => 6, // Geotechnical Investigation
                'contract_id' => 6,
                'start_date' => '2025-02-15',
                'end_date' => '2025-02-21',
                'total_hours' => 40.0,
                'hourly_rate' => 75.00,
                'total_amount' => 3000.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-02-22 09:00:00',
                'reviewed_at' => '2025-02-23 16:30:00',
                'reviewed_by' => 15, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 9,
                'freelancer_id' => 8, // Robert Anderson
                'company_id' => 16, // Geology Consultants
                'project_id' => 7, // Petroleum Geology Assessment
                'contract_id' => 7,
                'start_date' => '2025-01-01',
                'end_date' => '2025-01-07',
                'total_hours' => 40.0,
                'hourly_rate' => 100.00,
                'total_amount' => 4000.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-01-08 09:00:00',
                'reviewed_at' => '2025-01-09 14:20:00',
                'reviewed_by' => 16, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 10,
                'freelancer_id' => 2, // John Smith
                'company_id' => 17, // Resource Exploration Group
                'project_id' => 8, // Rare Earth Elements Exploration
                'contract_id' => 8,
                'start_date' => '2025-03-15',
                'end_date' => '2025-03-21',
                'total_hours' => 40.0,
                'hourly_rate' => 85.00,
                'total_amount' => 3400.00,
                'status_id' => 4, // Approved
                'submitted_at' => '2025-03-22 09:00:00',
                'reviewed_at' => '2025-03-23 11:45:00',
                'reviewed_by' => 17, // Company user
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Pending timesheets
            [
                'timesheet_id' => 11,
                'freelancer_id' => 2, // John Smith
                'company_id' => 10, // Northern Mining Corp
                'project_id' => 1, // Northern Gold Exploration Project
                'contract_id' => 1,
                'start_date' => '2025-01-29',
                'end_date' => '2025-02-04',
                'total_hours' => 40.0,
                'hourly_rate' => 85.00,
                'total_amount' => 3400.00,
                'status_id' => 2, // Submitted
                'submitted_at' => '2025-02-05 09:00:00',
                'reviewed_at' => null,
                'reviewed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 12,
                'freelancer_id' => 3, // Sarah Jones
                'company_id' => 11, // GeoData Analytics
                'project_id' => 2, // Geospatial Data Analysis
                'contract_id' => 2,
                'start_date' => '2025-02-15',
                'end_date' => '2025-02-21',
                'total_hours' => 39.0,
                'hourly_rate' => 90.00,
                'total_amount' => 3510.00,
                'status_id' => 2, // Submitted
                'submitted_at' => '2025-02-22 09:00:00',
                'reviewed_at' => null,
                'reviewed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'timesheet_id' => 13,
                'freelancer_id' => 4, // Michael Brown
                'company_id' => 12, // Exploration Corp International
                'project_id' => 3, // Copper-Nickel Exploration
                'contract_id' => 3,
                'start_date' => '2025-03-08',
                'end_date' => '2025-03-14',
                'total_hours' => 43.0,
                'hourly_rate' => 80.00,
                'total_amount' => 3440.00,
                'status_id' => 2, // Submitted
                'submitted_at' => '2025-03-15 09:00:00',
                'reviewed_at' => null,
                'reviewed_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('timesheets')->insert($timesheets);

        // Now create timesheet days for some of the timesheets
        $timesheetDays = [
            // Timesheet 1 - John Smith, Week 1
            ['day_id' => 1, 'timesheet_id' => 1, 'work_date' => '2025-01-15', 'hours_worked' => 8.0, 'description' => 'Field mapping and geological survey', 'tasks_completed' => 'Completed geological mapping of 2km section', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 2, 'timesheet_id' => 1, 'work_date' => '2025-01-16', 'hours_worked' => 8.0, 'description' => 'Core logging and sample analysis', 'tasks_completed' => 'Logged 50m of drill core, collected 20 samples', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 3, 'timesheet_id' => 1, 'work_date' => '2025-01-17', 'hours_worked' => 8.0, 'description' => 'Data analysis and report writing', 'tasks_completed' => 'Analyzed geological data, prepared preliminary report', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 4, 'timesheet_id' => 1, 'work_date' => '2025-01-18', 'hours_worked' => 8.0, 'description' => 'Field mapping continuation', 'tasks_completed' => 'Completed mapping of additional 1.5km section', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 5, 'timesheet_id' => 1, 'work_date' => '2025-01-19', 'hours_worked' => 8.0, 'description' => 'Data compilation and quality control', 'tasks_completed' => 'Compiled all field data, performed quality control checks', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],

            // Timesheet 2 - John Smith, Week 2
            ['day_id' => 6, 'timesheet_id' => 2, 'work_date' => '2025-01-22', 'hours_worked' => 8.0, 'description' => 'Advanced geological mapping', 'tasks_completed' => 'Completed detailed mapping of high-potential area', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 7, 'timesheet_id' => 2, 'work_date' => '2025-01-23', 'hours_worked' => 8.0, 'description' => 'Core logging and sample preparation', 'tasks_completed' => 'Logged 60m of drill core, prepared samples for analysis', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 8, 'timesheet_id' => 2, 'work_date' => '2025-01-24', 'hours_worked' => 8.0, 'description' => 'Geological interpretation', 'tasks_completed' => 'Interpreted geological structures and mineralization', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 9, 'timesheet_id' => 2, 'work_date' => '2025-01-25', 'hours_worked' => 8.0, 'description' => 'Field verification and sampling', 'tasks_completed' => 'Verified geological interpretations in field', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 10, 'timesheet_id' => 2, 'work_date' => '2025-01-26', 'hours_worked' => 10.0, 'description' => 'Report preparation and data analysis', 'tasks_completed' => 'Prepared comprehensive weekly report', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],

            // Timesheet 3 - Sarah Jones, Week 1
            ['day_id' => 11, 'timesheet_id' => 3, 'work_date' => '2025-02-01', 'hours_worked' => 8.0, 'description' => 'Seismic data processing', 'tasks_completed' => 'Processed seismic data for mining operations', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 12, 'timesheet_id' => 3, 'work_date' => '2025-02-02', 'hours_worked' => 8.0, 'description' => 'Gravity survey analysis', 'tasks_completed' => 'Analyzed gravity survey data for subsurface mapping', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 13, 'timesheet_id' => 3, 'work_date' => '2025-02-03', 'hours_worked' => 7.5, 'description' => 'Magnetic survey interpretation', 'tasks_completed' => 'Interpreted magnetic survey data', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 14, 'timesheet_id' => 3, 'work_date' => '2025-02-04', 'hours_worked' => 8.0, 'description' => 'Data integration and modeling', 'tasks_completed' => 'Integrated geophysical data with geological models', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 15, 'timesheet_id' => 3, 'work_date' => '2025-02-05', 'hours_worked' => 7.0, 'description' => 'Report preparation', 'tasks_completed' => 'Prepared geophysical analysis report', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],

            // Timesheet 4 - Sarah Jones, Week 2
            ['day_id' => 16, 'timesheet_id' => 4, 'work_date' => '2025-02-08', 'hours_worked' => 8.0, 'description' => 'Advanced seismic interpretation', 'tasks_completed' => 'Completed advanced seismic interpretation', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 17, 'timesheet_id' => 4, 'work_date' => '2025-02-09', 'hours_worked' => 8.0, 'description' => 'Electrical resistivity analysis', 'tasks_completed' => 'Analyzed electrical resistivity survey data', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 18, 'timesheet_id' => 4, 'work_date' => '2025-02-10', 'hours_worked' => 8.0, 'description' => 'Ground penetrating radar survey', 'tasks_completed' => 'Conducted GPR survey and data analysis', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 19, 'timesheet_id' => 4, 'work_date' => '2025-02-11', 'hours_worked' => 8.0, 'description' => 'Data quality control', 'tasks_completed' => 'Performed quality control on all geophysical data', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
            ['day_id' => 20, 'timesheet_id' => 4, 'work_date' => '2025-02-12', 'hours_worked' => 8.0, 'description' => 'Final report preparation', 'tasks_completed' => 'Prepared comprehensive geophysical report', 'is_billable' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('timesheet_days')->insert($timesheetDays);
    }
}
