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
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->foreign(['timesheet_id'], 'payment_requests_ibfk_1')->references(['timesheet_id'])->on('timesheets')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['freelancer_id'], 'payment_requests_ibfk_2')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['invoice_id'], 'payment_requests_ibfk_3')->references(['invoice_id'])->on('invoices')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['processed_by'], 'payment_requests_ibfk_4')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropForeign('payment_requests_ibfk_1');
            $table->dropForeign('payment_requests_ibfk_2');
            $table->dropForeign('payment_requests_ibfk_3');
            $table->dropForeign('payment_requests_ibfk_4');
        });
    }
};
