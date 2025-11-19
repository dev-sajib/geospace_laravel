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
            $table->integer('timesheet_id')->autoIncrement();
            $table->integer('contract_id');
            $table->integer('freelancer_id');
            $table->integer('company_id');
            $table->integer('project_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('status_id')->default(1)->nullable();
            $table->string('status_display_name', 50)->nullable();
            $table->decimal('total_hours', 6, 2)->default(0.00);
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->integer('reviewed_by')->nullable();
            $table->timestamp('payment_requested_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->integer('resubmission_count')->default(0);
            $table->timestamp('last_resubmitted_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('contract_id');
            $table->index('freelancer_id', 'idx_timesheets_freelancer');
            $table->index('company_id', 'idx_timesheets_company');
            $table->index('project_id', 'idx_timesheets_project');
            $table->index('status_id', 'idx_timesheets_status');
            $table->index(['start_date', 'end_date'], 'idx_timesheets_dates');
            $table->index('reviewed_by');

            $table->foreign('contract_id', 'timesheets_ibfk_1')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            $table->foreign('freelancer_id', 'timesheets_ibfk_2')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('company_id', 'timesheets_ibfk_3')
                ->references('company_id')
                ->on('company_details')
                ->onDelete('cascade');

            $table->foreign('project_id', 'timesheets_ibfk_4')
                ->references('project_id')
                ->on('projects')
                ->onDelete('cascade');

            $table->foreign('status_id', 'timesheets_ibfk_5')
                ->references('status_id')
                ->on('timesheet_status');

            $table->foreign('reviewed_by', 'timesheets_ibfk_6')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');
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
