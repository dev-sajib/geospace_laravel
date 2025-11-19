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
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('timesheet_id', 'idx_payment_requests_timesheet');
            $table->index('freelancer_id', 'idx_payment_requests_freelancer');
            $table->index('invoice_id');
            $table->index('status', 'idx_payment_requests_status');
            $table->index('processed_by');

            $table->foreign('timesheet_id', 'payment_requests_ibfk_1')
                ->references('timesheet_id')
                ->on('timesheets')
                ->onDelete('cascade');

            $table->foreign('freelancer_id', 'payment_requests_ibfk_2')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('invoice_id', 'payment_requests_ibfk_3')
                ->references('invoice_id')
                ->on('invoices')
                ->onDelete('set null');

            $table->foreign('processed_by', 'payment_requests_ibfk_4')
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
        Schema::dropIfExists('payment_requests');
    }
};
