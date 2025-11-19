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
        Schema::create('freelancer_bank_information', function (Blueprint $table) {
            $table->integer('bank_info_id')->autoIncrement();
            $table->integer('freelancer_id');
            $table->string('bank_name', 255);
            $table->string('account_holder_name', 255);
            $table->string('account_number', 50);
            $table->enum('account_type', ['Checking', 'Savings', 'Business'])->default('Checking');
            $table->string('routing_number', 30)->nullable();
            $table->string('swift_code', 20)->nullable();
            $table->string('iban', 50)->nullable();
            $table->text('bank_address')->nullable();
            $table->string('bank_city', 100)->nullable();
            $table->string('bank_state', 100)->nullable();
            $table->string('bank_country', 100);
            $table->string('bank_postal_code', 20)->nullable();
            $table->string('intermediate_bank_name', 255)->nullable();
            $table->string('intermediate_swift_code', 20)->nullable();
            $table->string('currency', 3)->default('CAD');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->string('verification_document', 500)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Pending_Verification'])->default('Pending_Verification');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->unique(['freelancer_id', 'is_primary'], 'unique_primary_bank');
            $table->index('freelancer_id', 'idx_freelancer_bank');
            $table->index('status', 'idx_bank_status');

            $table->foreign('freelancer_id', 'freelancer_bank_information_ibfk_1')
                ->references('freelancer_detail_id')
                ->on('freelancer_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_bank_information');
    }
};
