<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheet_days', function (Blueprint $table) {
            $table->integer('day_id')->autoIncrement();
            $table->integer('timesheet_id');
            $table->date('work_date');
            $table->string('day_name', 20);
            $table->integer('day_number');
            $table->decimal('hours_worked', 4, 2)->default(0.00);
            $table->text('task_description')->nullable();
            $table->timestamps();

            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('cascade');
            
            $table->index('timesheet_id', 'idx_timesheet_days_timesheet');
            $table->index('work_date', 'idx_timesheet_days_date');
        });

        Schema::create('timesheet_day_comments', function (Blueprint $table) {
            $table->integer('comment_id')->autoIncrement();
            $table->integer('day_id');
            $table->integer('timesheet_id');
            $table->integer('comment_by');
            $table->enum('comment_type', ['Company', 'Freelancer']);
            $table->text('comment_text');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('day_id')
                  ->references('day_id')
                  ->on('timesheet_days')
                  ->onDelete('cascade');
            
            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('cascade');
            
            $table->foreign('comment_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->index('day_id', 'idx_comments_day');
            $table->index('timesheet_id', 'idx_comments_timesheet');
            $table->index('comment_type', 'idx_comments_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheet_day_comments');
        Schema::dropIfExists('timesheet_days');
    }
};
