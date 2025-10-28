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
        Schema::create('contracts', function (Blueprint $table) {
            $table->integer('contract_id', true);
            $table->integer('project_id')->index('idx_contracts_project_id');
            $table->integer('freelancer_id')->index('idx_contracts_freelancer_id');
            $table->integer('company_id')->index('idx_contracts_company_id');
            $table->string('contract_title');
            $table->text('contract_description')->nullable();
            $table->decimal('hourly_rate', 10)->nullable();
            $table->decimal('total_amount', 12)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['Pending', 'Active', 'Completed', 'Cancelled', 'Disputed'])->nullable()->default('Pending')->index('idx_contracts_status');
            $table->string('payment_terms')->nullable();
            $table->json('milestones')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
