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
        Schema::create('timesheet_days', function (Blueprint $table) {
            $table->integer('day_id')->autoIncrement();
            $table->integer('timesheet_id');
            $table->date('work_date');
            $table->string('day_name', 20);
            $table->integer('day_number');
            $table->decimal('hours_worked', 4, 2)->default(0.00);
            $table->text('task_description')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('timesheet_id', 'idx_timesheet_days_timesheet');
            $table->index('work_date', 'idx_timesheet_days_date');

            $table->foreign('timesheet_id', 'timesheet_days_ibfk_1')
                ->references('timesheet_id')
                ->on('timesheets')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet_days');
    }
};
