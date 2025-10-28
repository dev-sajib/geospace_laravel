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
        Schema::create('timesheets', function (Blueprint $table) {
            $table->integer('timesheet_id', true);
            $table->integer('contract_id')->index('contract_id');
            $table->integer('freelancer_id')->index('idx_timesheets_freelancer');
            $table->integer('company_id')->index('idx_timesheets_company');
            $table->integer('project_id')->index('idx_timesheets_project');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('status_id')->nullable()->default(1)->index('idx_timesheets_status');
            $table->string('status_display_name', 50)->nullable();
            $table->decimal('total_hours', 6)->default(0);
            $table->decimal('hourly_rate', 10);
            $table->decimal('total_amount', 12)->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->integer('reviewed_by')->nullable()->index('reviewed_by');
            $table->timestamp('payment_requested_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->integer('resubmission_count')->nullable()->default(0);
            $table->timestamp('last_resubmitted_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['start_date', 'end_date'], 'idx_timesheets_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
