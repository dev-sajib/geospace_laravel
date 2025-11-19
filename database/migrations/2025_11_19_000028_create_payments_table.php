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
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('invoice_id', 'idx_payments_invoice');
            $table->index('timesheet_id', 'idx_payments_timesheet');
            $table->index('payment_request_id');
            $table->index('status', 'idx_payments_status');
            $table->index('transaction_id', 'idx_payments_transaction');
            $table->index('verified_by');

            $table->foreign('invoice_id', 'payments_ibfk_1')
                ->references('invoice_id')
                ->on('invoices')
                ->onDelete('set null');

            $table->foreign('timesheet_id', 'payments_ibfk_2')
                ->references('timesheet_id')
                ->on('timesheets')
                ->onDelete('set null');

            $table->foreign('payment_request_id', 'payments_ibfk_3')
                ->references('request_id')
                ->on('payment_requests')
                ->onDelete('set null');

            $table->foreign('verified_by', 'payments_ibfk_4')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
