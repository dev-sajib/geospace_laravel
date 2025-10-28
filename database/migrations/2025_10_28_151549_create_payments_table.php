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
            $table->integer('payment_id', true);
            $table->integer('invoice_id')->nullable()->index('idx_payments_invoice');
            $table->integer('timesheet_id')->nullable()->index('idx_payments_timesheet');
            $table->integer('payment_request_id')->nullable()->index('payment_request_id');
            $table->enum('payment_type', ['Company_to_Platform', 'Platform_to_Freelancer']);
            $table->decimal('amount', 12);
            $table->string('currency', 3)->nullable()->default('CAD');
            $table->enum('status', ['Pending', 'Completed', 'Failed', 'Refunded'])->nullable()->default('Pending')->index('idx_payments_status');
            $table->string('transaction_id')->nullable()->index('idx_payments_transaction');
            $table->string('payment_method', 100)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->integer('verified_by')->nullable()->index('verified_by');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
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
