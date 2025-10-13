<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->integer('request_id')->autoIncrement();
            $table->integer('timesheet_id');
            $table->integer('freelancer_id');
            $table->integer('invoice_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Processing', 'Completed'])->default('Pending');
            $table->integer('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('cascade');
            
            $table->foreign('freelancer_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('invoice_id')
                  ->references('invoice_id')
                  ->on('invoices')
                  ->onDelete('set null');
            
            $table->foreign('processed_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
            
            $table->index('timesheet_id', 'idx_payment_requests_timesheet');
            $table->index('freelancer_id', 'idx_payment_requests_freelancer');
            $table->index('status', 'idx_payment_requests_status');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->integer('payment_id')->autoIncrement();
            $table->integer('invoice_id')->nullable();
            $table->integer('timesheet_id')->nullable();
            $table->integer('payment_request_id')->nullable();
            $table->enum('payment_type', ['Company_to_Platform', 'Platform_to_Freelancer']);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('CAD');
            $table->enum('status', ['Pending', 'Completed', 'Failed', 'Refunded'])->default('Pending');
            $table->string('transaction_id', 255)->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->integer('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')
                  ->references('invoice_id')
                  ->on('invoices')
                  ->onDelete('set null');
            
            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('set null');
            
            $table->foreign('payment_request_id')
                  ->references('request_id')
                  ->on('payment_requests')
                  ->onDelete('set null');
            
            $table->foreign('verified_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
            
            $table->index('invoice_id', 'idx_payments_invoice');
            $table->index('timesheet_id', 'idx_payments_timesheet');
            $table->index('status', 'idx_payments_status');
            $table->index('transaction_id', 'idx_payments_transaction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_requests');
    }
};
