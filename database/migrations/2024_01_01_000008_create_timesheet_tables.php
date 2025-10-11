<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheet_status', function (Blueprint $table) {
            $table->id('status_id');
            $table->string('status_name', 50)->unique();
            $table->text('status_description')->nullable();
            $table->string('status_color', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('timesheets', function (Blueprint $table) {
            $table->id('timesheet_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->unsignedBigInteger('status_id')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('freelancer_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('company_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('project_id')
                  ->references('project_id')
                  ->on('projects')
                  ->onDelete('cascade');
            
            $table->foreign('contract_id')
                  ->references('contract_id')
                  ->on('contracts')
                  ->onDelete('set null');
            
            $table->foreign('status_id')
                  ->references('status_id')
                  ->on('timesheet_status')
                  ->onDelete('restrict');
            
            $table->foreign('approved_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
            
            $table->foreign('rejected_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
            
            $table->index('freelancer_id');
            $table->index('company_id');
            $table->index('project_id');
            $table->index('status_id');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
        Schema::dropIfExists('timesheet_status');
    }
};
