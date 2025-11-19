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
            $table->integer('ticket_id')->autoIncrement();
            $table->string('ticket_number', 20)->unique()->nullable();
            $table->integer('contract_id');
            $table->integer('created_by');
            $table->integer('assigned_to')->nullable();
            $table->integer('status_id')->default(1)->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->string('category', 100)->nullable();
            $table->string('subject', 255);
            $table->text('description');
            $table->string('attachment', 500)->nullable();
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('contract_id', 'idx_dispute_tickets_contract_id');
            $table->index('created_by', 'idx_dispute_tickets_created_by');
            $table->index('assigned_to');
            $table->index('status_id', 'idx_dispute_tickets_status_id');

            $table->foreign('contract_id', 'dispute_tickets_ibfk_1')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            $table->foreign('created_by', 'dispute_tickets_ibfk_2')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('assigned_to', 'dispute_tickets_ibfk_3')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('status_id', 'dispute_tickets_ibfk_4')
                ->references('status_id')
                ->on('dispute_status');
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
