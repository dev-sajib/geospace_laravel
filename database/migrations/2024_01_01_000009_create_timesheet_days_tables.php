<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheet_days', function (Blueprint $table) {
            $table->id('day_id');
            $table->unsignedBigInteger('timesheet_id');
            $table->date('work_date');
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('tasks_completed')->nullable();
            $table->boolean('is_billable')->default(true);
            $table->timestamps();

            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('cascade');
            
            $table->index('timesheet_id');
            $table->index('work_date');
        });

        Schema::create('timesheet_day_comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->unsignedBigInteger('day_id');
            $table->unsignedBigInteger('timesheet_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment_text');
            $table->enum('comment_type', ['Approval', 'Rejection', 'Query', 'General'])->default('General');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('day_id')
                  ->references('day_id')
                  ->on('timesheet_days')
                  ->onDelete('cascade');
            
            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('cascade');
            
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->index('day_id');
            $table->index('timesheet_id');
            $table->index('comment_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheet_day_comments');
        Schema::dropIfExists('timesheet_days');
    }
};
