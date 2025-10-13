<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->timestamps();

            $table->foreign('project_id')
                  ->references('project_id')
                  ->on('projects')
                  ->onDelete('cascade');
            
            $table->foreign('freelancer_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('company_id')
                  ->references('company_id')
                  ->on('company_details')
                  ->onDelete('cascade');
            
            $table->index('project_id', 'idx_contracts_project_id');
            $table->index('freelancer_id', 'idx_contracts_freelancer_id');
            $table->index('company_id', 'idx_contracts_company_id');
            $table->index('status', 'idx_contracts_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
