<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->foreign(['contract_id'], 'timesheets_ibfk_1')->references(['contract_id'])->on('contracts')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['freelancer_id'], 'timesheets_ibfk_2')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['company_id'], 'timesheets_ibfk_3')->references(['company_id'])->on('company_details')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['project_id'], 'timesheets_ibfk_4')->references(['project_id'])->on('projects')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['status_id'], 'timesheets_ibfk_5')->references(['status_id'])->on('timesheet_status')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['reviewed_by'], 'timesheets_ibfk_6')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropForeign('timesheets_ibfk_1');
            $table->dropForeign('timesheets_ibfk_2');
            $table->dropForeign('timesheets_ibfk_3');
            $table->dropForeign('timesheets_ibfk_4');
            $table->dropForeign('timesheets_ibfk_5');
            $table->dropForeign('timesheets_ibfk_6');
        });
    }
};
