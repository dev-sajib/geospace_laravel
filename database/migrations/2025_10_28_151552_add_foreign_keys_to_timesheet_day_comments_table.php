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
        Schema::table('timesheet_day_comments', function (Blueprint $table) {
            $table->foreign(['day_id'], 'timesheet_day_comments_ibfk_1')->references(['day_id'])->on('timesheet_days')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['timesheet_id'], 'timesheet_day_comments_ibfk_2')->references(['timesheet_id'])->on('timesheets')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['comment_by'], 'timesheet_day_comments_ibfk_3')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timesheet_day_comments', function (Blueprint $table) {
            $table->dropForeign('timesheet_day_comments_ibfk_1');
            $table->dropForeign('timesheet_day_comments_ibfk_2');
            $table->dropForeign('timesheet_day_comments_ibfk_3');
        });
    }
};
