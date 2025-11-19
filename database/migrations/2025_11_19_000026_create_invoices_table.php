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
            $table->integer('invoice_id')->autoIncrement();
            $table->integer('timesheet_id');
            $table->integer('contract_id');
            $table->integer('company_id');
            $table->integer('freelancer_id');
            $table->string('invoice_number', 100)->unique();
            $table->date('invoice_date');
            $table->decimal('total_hours', 6, 2);
            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 3)->default('CAD');
            $table->enum('status', ['Generated', 'Sent', 'Paid', 'Overdue', 'Cancelled'])->default('Generated');
            $table->enum('freelancer_status', ['pending', 'requested', 'complete', 'send'])->default('pending');
            $table->enum('company_status', ['pending', 'requested', 'complete', 'send'])->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('timesheet_id', 'idx_invoices_timesheet');
            $table->index('contract_id');
            $table->index('company_id', 'idx_invoices_company');
            $table->index('freelancer_id', 'idx_invoices_freelancer');
            $table->index('status', 'idx_invoices_status');
            $table->index('invoice_number', 'idx_invoices_number');

            $table->foreign('timesheet_id', 'invoices_ibfk_1')
                ->references('timesheet_id')
                ->on('timesheets')
                ->onDelete('cascade');

            $table->foreign('contract_id', 'invoices_ibfk_2')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            $table->foreign('company_id', 'invoices_ibfk_3')
                ->references('company_id')
                ->on('company_details')
                ->onDelete('cascade');

            $table->foreign('freelancer_id', 'invoices_ibfk_4')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
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
