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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->integer('request_id', true);
            $table->integer('timesheet_id')->index('idx_payment_requests_timesheet');
            $table->integer('freelancer_id')->index('idx_payment_requests_freelancer');
            $table->integer('invoice_id')->nullable()->index('invoice_id');
            $table->decimal('amount', 12);
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Processing', 'Completed'])->nullable()->default('Pending')->index('idx_payment_requests_status');
            $table->integer('processed_by')->nullable()->index('processed_by');
            $table->timestamp('processed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
