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
            $table->integer('day_id', true);
            $table->integer('timesheet_id')->index('idx_timesheet_days_timesheet');
            $table->date('work_date')->index('idx_timesheet_days_date');
            $table->string('day_name', 20);
            $table->integer('day_number');
            $table->decimal('hours_worked', 4)->default(0);
            $table->text('task_description')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
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
