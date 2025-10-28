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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign(['invoice_id'], 'payments_ibfk_1')->references(['invoice_id'])->on('invoices')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['timesheet_id'], 'payments_ibfk_2')->references(['timesheet_id'])->on('timesheets')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['payment_request_id'], 'payments_ibfk_3')->references(['request_id'])->on('payment_requests')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['verified_by'], 'payments_ibfk_4')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign('payments_ibfk_1');
            $table->dropForeign('payments_ibfk_2');
            $table->dropForeign('payments_ibfk_3');
            $table->dropForeign('payments_ibfk_4');
        });
    }
};
