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
        Schema::create('timesheet_day_comments', function (Blueprint $table) {
            $table->integer('comment_id')->autoIncrement();
            $table->integer('day_id');
            $table->integer('timesheet_id');
            $table->integer('comment_by');
            $table->enum('comment_type', ['Company', 'Freelancer']);
            $table->text('comment_text');
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('day_id', 'idx_comments_day');
            $table->index('timesheet_id', 'idx_comments_timesheet');
            $table->index('comment_type', 'idx_comments_type');
            $table->index('comment_by');

            $table->foreign('day_id', 'timesheet_day_comments_ibfk_1')
                ->references('day_id')
                ->on('timesheet_days')
                ->onDelete('cascade');

            $table->foreign('timesheet_id', 'timesheet_day_comments_ibfk_2')
                ->references('timesheet_id')
                ->on('timesheets')
                ->onDelete('cascade');

            $table->foreign('comment_by', 'timesheet_day_comments_ibfk_3')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet_day_comments');
    }
};
