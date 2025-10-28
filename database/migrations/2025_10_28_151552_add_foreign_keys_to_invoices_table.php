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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign(['timesheet_id'], 'invoices_ibfk_1')->references(['timesheet_id'])->on('timesheets')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['contract_id'], 'invoices_ibfk_2')->references(['contract_id'])->on('contracts')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['company_id'], 'invoices_ibfk_3')->references(['company_id'])->on('company_details')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['freelancer_id'], 'invoices_ibfk_4')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_ibfk_1');
            $table->dropForeign('invoices_ibfk_2');
            $table->dropForeign('invoices_ibfk_3');
            $table->dropForeign('invoices_ibfk_4');
        });
    }
};
