<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('timesheet_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->decimal('requested_amount', 12, 2);
            $table->text('request_notes')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Paid'])->default('Pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();

            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('cascade');
            
            $table->foreign('freelancer_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('processed_by')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
            
            $table->index('timesheet_id');
            $table->index('freelancer_id');
            $table->index('status');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('timesheet_id');
            $table->decimal('payment_amount', 12, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['Bank Transfer', 'PayPal', 'Stripe', 'Check', 'Other'])->default('Bank Transfer');
            $table->string('transaction_id', 255)->nullable();
            $table->enum('status', ['Pending', 'Completed', 'Failed', 'Refunded'])->default('Pending');
            $table->text('payment_notes')->nullable();
            $table->string('payment_receipt', 500)->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')
                  ->references('invoice_id')
                  ->on('invoices')
                  ->onDelete('set null');
            
            $table->foreign('timesheet_id')
                  ->references('timesheet_id')
                  ->on('timesheets')
                  ->onDelete('cascade');
            
            $table->index('invoice_id');
            $table->index('timesheet_id');
            $table->index('status');
            $table->index('transaction_id');
        });

        Schema::create('freelancer_earnings', function (Blueprint $table) {
            $table->id('earning_id');
            $table->unsignedBigInteger('freelancer_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('earning_date');
            $table->enum('earning_type', ['Timesheet Payment', 'Bonus', 'Refund', 'Other'])->default('Timesheet Payment');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('freelancer_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('payment_id')
                  ->references('payment_id')
                  ->on('payments')
                  ->onDelete('set null');
            
            $table->index('freelancer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancer_earnings');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_requests');
    }
};
