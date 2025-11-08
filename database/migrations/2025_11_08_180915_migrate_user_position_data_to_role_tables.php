<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate user_position data to appropriate role tables

        // For freelancers (role_id = 2): user_position -> freelancer_details.designation
        DB::statement("
            UPDATE freelancer_details fd
            JOIN users u ON fd.user_id = u.user_id
            SET fd.designation = u.user_position
            WHERE u.role_id = 2 AND u.user_position IS NOT NULL
        ");

        // For companies (role_id = 3): user_position -> company_details.contact_designation
        DB::statement("
            UPDATE company_details cd
            JOIN users u ON cd.user_id = u.user_id
            SET cd.contact_designation = u.user_position
            WHERE u.role_id = 3 AND u.user_position IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse migration: move data back to user_position

        // From freelancer_details.designation -> users.user_position
        DB::statement("
            UPDATE users u
            JOIN freelancer_details fd ON u.user_id = fd.user_id
            SET u.user_position = fd.designation
            WHERE u.role_id = 2 AND fd.designation IS NOT NULL
        ");

        // From company_details.contact_designation -> users.user_position
        DB::statement("
            UPDATE users u
            JOIN company_details cd ON u.user_id = cd.user_id
            SET u.user_position = cd.contact_designation
            WHERE u.role_id = 3 AND cd.contact_designation IS NOT NULL
        ");
    }
};
