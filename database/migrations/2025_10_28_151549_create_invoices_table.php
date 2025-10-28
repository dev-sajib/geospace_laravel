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
        Schema::create('invoices', function (Blueprint $table) {
            $table->integer('invoice_id', true);
            $table->integer('timesheet_id')->index('idx_invoices_timesheet');
            $table->integer('contract_id')->index('contract_id');
            $table->integer('company_id')->index('idx_invoices_company');
            $table->integer('freelancer_id')->index('idx_invoices_freelancer');
            $table->string('invoice_number', 100)->index('idx_invoices_number');
            $table->date('invoice_date');
            $table->decimal('total_hours', 6);
            $table->decimal('hourly_rate', 10);
            $table->decimal('subtotal', 12);
            $table->decimal('tax_amount', 12)->nullable()->default(0);
            $table->decimal('total_amount', 12);
            $table->string('currency', 3)->nullable()->default('CAD');
            $table->enum('status', ['Generated', 'Sent', 'Paid', 'Overdue', 'Cancelled'])->nullable()->default('Generated')->index('idx_invoices_status');
            $table->enum('freelancer_status', ['pending', 'requested', 'complete', 'send'])->nullable()->default('pending');
            $table->enum('company_status', ['pending', 'requested', 'complete', 'send'])->nullable()->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->unique(['invoice_number'], 'invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
