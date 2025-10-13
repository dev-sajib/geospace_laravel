<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispute_status', function (Blueprint $table) {
            $table->integer('status_id')->autoIncrement();
            $table->string('status_name', 50)->unique();
            $table->text('status_description')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('dispute_tickets', function (Blueprint $table) {
            $table->integer('ticket_id')->autoIncrement();
            $table->integer('contract_id');
            $table->integer('created_by');
            $table->integer('assigned_to')->nullable();
            $table->integer('status_id')->default(1);
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->string('category', 100)->nullable();
            $table->string('subject', 255);
            $table->text('description');
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('contract_id')
                  ->references('contract_id')
                  ->on('contracts')
                  ->onDelete('cascade');
            
            $table->foreign('created_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('assigned_to')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
            
            $table->foreign('status_id')
                  ->references('status_id')
                  ->on('dispute_status');
            
            $table->index('contract_id', 'idx_dispute_tickets_contract_id');
            $table->index('created_by', 'idx_dispute_tickets_created_by');
            $table->index('status_id', 'idx_dispute_tickets_status_id');
        });

        Schema::create('dispute_messages', function (Blueprint $table) {
            $table->integer('message_id')->autoIncrement();
            $table->integer('ticket_id');
            $table->integer('sender_id');
            $table->text('message_text');
            $table->string('attachment_url', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('ticket_id')
                  ->references('ticket_id')
                  ->on('dispute_tickets')
                  ->onDelete('cascade');
            
            $table->foreign('sender_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispute_messages');
        Schema::dropIfExists('dispute_tickets');
        Schema::dropIfExists('dispute_status');
    }
};
