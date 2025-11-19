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
            $table->integer('contract_id')->autoIncrement();
            $table->integer('project_id');
            $table->integer('freelancer_id');
            $table->integer('company_id');
            $table->string('contract_title', 255);
            $table->text('contract_description')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['Pending', 'Active', 'Completed', 'Cancelled', 'Disputed'])->default('Pending');
            $table->string('payment_terms', 255)->nullable();
            $table->json('milestones')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('project_id', 'idx_contracts_project_id');
            $table->index('freelancer_id', 'idx_contracts_freelancer_id');
            $table->index('company_id', 'idx_contracts_company_id');
            $table->index('status', 'idx_contracts_status');

            $table->foreign('project_id', 'contracts_ibfk_1')
                ->references('project_id')
                ->on('projects')
                ->onDelete('cascade');

            $table->foreign('freelancer_id', 'contracts_ibfk_2')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('company_id', 'contracts_ibfk_3')
                ->references('company_id')
                ->on('company_details')
                ->onDelete('cascade');
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
