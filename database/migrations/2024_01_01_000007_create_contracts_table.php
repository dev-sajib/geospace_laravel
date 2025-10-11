<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id('contract_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->unsignedBigInteger('company_id');
            $table->string('contract_title', 255);
            $table->text('contract_description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('contract_value', 12, 2);
            $table->enum('payment_terms', ['Fixed Price', 'Hourly', 'Milestone-based'])->default('Fixed Price');
            $table->enum('status', ['Active', 'Completed', 'Terminated', 'On Hold'])->default('Active');
            $table->text('terms_and_conditions')->nullable();
            $table->string('contract_document', 500)->nullable();
            $table->timestamp('signed_at')->nullable();
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
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->index('project_id');
            $table->index('freelancer_id');
            $table->index('company_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
