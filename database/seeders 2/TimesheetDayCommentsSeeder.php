<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimesheetDayCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder populates the timesheet_day_comments table with sample comments.
     * 
     * Relationships:
     * - day_id: References timesheet_days.day_id
     * - timesheet_id: References timesheets.timesheet_id
     * - user_id: References users.user_id (comment author)
     * 
     * Comment Types: 'Approval', 'Rejection', 'Query', 'General'
     */
    public function run(): void
    {
        $comments = [
            // Comments on Timesheet 1 (John Smith - Approved) - day_id: 1-5
            [
                'comment_id' => 1,
                'day_id' => 1,
                'timesheet_id' => 1,
                'user_id' => 10, // Company user
                'comment_text' => 'Great work on the field mapping. The geological survey results are very detailed.',
                'comment_type' => 'Approval',
                'created_at' => '2025-01-23 10:30:00',
            ],
            [
                'comment_id' => 2,
                'day_id' => 2,
                'timesheet_id' => 1,
                'user_id' => 2, // John Smith (Freelancer)
                'comment_text' => 'Core samples have been sent to the lab for detailed analysis. Results expected in 3-5 business days.',
                'comment_type' => 'General',
                'created_at' => '2025-01-16 16:45:00',
            ],
            [
                'comment_id' => 3,
                'day_id' => 3,
                'timesheet_id' => 1,
                'user_id' => 10, // Company user
                'comment_text' => 'Please ensure all safety protocols were followed during data analysis.',
                'comment_type' => 'Query',
                'created_at' => '2025-01-23 11:00:00',
            ],
            [
                'comment_id' => 4,
                'day_id' => 3,
                'timesheet_id' => 1,
                'user_id' => 2, // John Smith (Freelancer)
                'comment_text' => 'Yes, all safety protocols were strictly adhered to. Documentation attached to the project folder.',
                'comment_type' => 'General',
                'created_at' => '2025-01-23 14:20:00',
            ],
            [
                'comment_id' => 5,
                'day_id' => 4,
                'timesheet_id' => 1,
                'user_id' => 10, // Company user
                'comment_text' => 'Field mapping continuation shows excellent progress. Keep up the good work.',
                'comment_type' => 'Approval',
                'created_at' => '2025-01-23 11:30:00',
            ],
            [
                'comment_id' => 6,
                'day_id' => 5,
                'timesheet_id' => 1,
                'user_id' => 10, // Company user
                'comment_text' => 'All data has been compiled and quality checked. Ready for final review.',
                'comment_type' => 'General',
                'created_at' => '2025-01-19 17:00:00',
            ],

            // Comments on Timesheet 2 (John Smith - Approved) - day_id: 6-10
            [
                'comment_id' => 7,
                'day_id' => 6,
                'timesheet_id' => 2,
                'user_id' => 10, // Company user
                'comment_text' => 'Excellent interpretation of the structural features. This will help in planning the next phase.',
                'comment_type' => 'Approval',
                'created_at' => '2025-01-30 09:15:00',
            ],
            [
                'comment_id' => 8,
                'day_id' => 8,
                'timesheet_id' => 2,
                'user_id' => 2, // John Smith (Freelancer)
                'comment_text' => 'Identified potential mineralization zones in sectors 3 and 5. Recommend follow-up drilling.',
                'comment_type' => 'General',
                'created_at' => '2025-01-24 15:30:00',
            ],
            [
                'comment_id' => 9,
                'day_id' => 9,
                'timesheet_id' => 2,
                'user_id' => 2, // John Smith (Freelancer)
                'comment_text' => 'Field verification completed successfully. All interpretations verified on site.',
                'comment_type' => 'General',
                'created_at' => '2025-01-25 16:00:00',
            ],
            [
                'comment_id' => 10,
                'day_id' => 10,
                'timesheet_id' => 2,
                'user_id' => 10, // Company user
                'comment_text' => 'Comprehensive weekly report received. All deliverables met expectations.',
                'comment_type' => 'Approval',
                'created_at' => '2025-01-30 10:00:00',
            ],

            // Comments on Timesheet 3 (Sarah Jones - Approved) - day_id: 11-15
            [
                'comment_id' => 11,
                'day_id' => 11,
                'timesheet_id' => 3,
                'user_id' => 11, // Mineral Resources Ltd (Company)
                'comment_text' => 'The gravity data interpretation looks accurate. Please confirm the anomaly coordinates.',
                'comment_type' => 'Query',
                'created_at' => '2025-02-06 10:45:00',
            ],
            [
                'comment_id' => 12,
                'day_id' => 11,
                'timesheet_id' => 3,
                'user_id' => 3, // Sarah Jones (Freelancer)
                'comment_text' => 'Anomaly coordinates confirmed: Lat 48.2567°N, Long -78.9012°W. High confidence reading.',
                'comment_type' => 'General',
                'created_at' => '2025-02-06 13:20:00',
            ],
            [
                'comment_id' => 13,
                'day_id' => 12,
                'timesheet_id' => 3,
                'user_id' => 11, // Company user
                'comment_text' => 'Gravity survey analysis is thorough and well-documented.',
                'comment_type' => 'Approval',
                'created_at' => '2025-02-06 11:00:00',
            ],
            [
                'comment_id' => 14,
                'day_id' => 13,
                'timesheet_id' => 3,
                'user_id' => 11, // Company user
                'comment_text' => 'Magnetic survey data is comprehensive. Approved for processing.',
                'comment_type' => 'Approval',
                'created_at' => '2025-02-06 14:00:00',
            ],
            [
                'comment_id' => 15,
                'day_id' => 14,
                'timesheet_id' => 3,
                'user_id' => 3, // Sarah Jones (Freelancer)
                'comment_text' => 'Geophysical data integrated successfully with geological models. Strong correlation observed.',
                'comment_type' => 'General',
                'created_at' => '2025-02-04 15:30:00',
            ],
            [
                'comment_id' => 16,
                'day_id' => 15,
                'timesheet_id' => 3,
                'user_id' => 3, // Sarah Jones (Freelancer)
                'comment_text' => 'Geophysical report includes all required maps and cross-sections as per contract specifications.',
                'comment_type' => 'General',
                'created_at' => '2025-02-05 16:30:00',
            ],

            // Comments on Timesheet 4 (Sarah Jones - Approved) - day_id: 16-20
            [
                'comment_id' => 17,
                'day_id' => 16,
                'timesheet_id' => 4,
                'user_id' => 11, // Company user
                'comment_text' => 'Seismic interpretation shows promising results. Great attention to detail.',
                'comment_type' => 'Approval',
                'created_at' => '2025-02-13 11:30:00',
            ],
            [
                'comment_id' => 18,
                'day_id' => 17,
                'timesheet_id' => 4,
                'user_id' => 3, // Sarah Jones (Freelancer)
                'comment_text' => 'Electrical resistivity analysis completed. Results show clear subsurface conductivity patterns.',
                'comment_type' => 'General',
                'created_at' => '2025-02-09 16:00:00',
            ],
            [
                'comment_id' => 19,
                'day_id' => 18,
                'timesheet_id' => 4,
                'user_id' => 3, // Sarah Jones (Freelancer)
                'comment_text' => 'GPR survey revealed subsurface structures consistent with expected geological formations.',
                'comment_type' => 'General',
                'created_at' => '2025-02-10 17:00:00',
            ],
            [
                'comment_id' => 20,
                'day_id' => 18,
                'timesheet_id' => 4,
                'user_id' => 11, // Company user
                'comment_text' => 'GPR data quality is excellent. Proceeding with interpretation.',
                'comment_type' => 'Approval',
                'created_at' => '2025-02-13 10:00:00',
            ],
            [
                'comment_id' => 21,
                'day_id' => 19,
                'timesheet_id' => 4,
                'user_id' => 11, // Company user
                'comment_text' => 'Quality control procedures are thorough. All data validated successfully.',
                'comment_type' => 'Approval',
                'created_at' => '2025-02-13 14:30:00',
            ],
            [
                'comment_id' => 22,
                'day_id' => 20,
                'timesheet_id' => 4,
                'user_id' => 11, // Company user
                'comment_text' => 'Final report is excellent. All deliverables met. Well done!',
                'comment_type' => 'Approval',
                'created_at' => '2025-02-13 16:45:00',
            ],
        ];

        DB::table('timesheet_day_comments')->insert($comments);
    }
}
