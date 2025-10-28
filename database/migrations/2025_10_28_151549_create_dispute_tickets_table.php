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
        Schema::create('dispute_tickets', function (Blueprint $table) {
            $table->integer('ticket_id', true);
            $table->string('ticket_number', 20)->nullable()->unique('ticket_number');
            $table->integer('contract_id')->index('idx_dispute_tickets_contract_id');
            $table->integer('created_by')->index('idx_dispute_tickets_created_by');
            $table->integer('assigned_to')->nullable()->index('assigned_to');
            $table->integer('status_id')->nullable()->default(1)->index('idx_dispute_tickets_status_id');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->nullable()->default('Medium');
            $table->string('category', 100)->nullable();
            $table->string('subject');
            $table->text('description');
            $table->string('attachment', 500)->nullable();
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispute_tickets');
    }
};
