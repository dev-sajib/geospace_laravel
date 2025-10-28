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
        Schema::table('dispute_tickets', function (Blueprint $table) {
            $table->foreign(['contract_id'], 'dispute_tickets_ibfk_1')->references(['contract_id'])->on('contracts')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['created_by'], 'dispute_tickets_ibfk_2')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['assigned_to'], 'dispute_tickets_ibfk_3')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['status_id'], 'dispute_tickets_ibfk_4')->references(['status_id'])->on('dispute_status')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispute_tickets', function (Blueprint $table) {
            $table->dropForeign('dispute_tickets_ibfk_1');
            $table->dropForeign('dispute_tickets_ibfk_2');
            $table->dropForeign('dispute_tickets_ibfk_3');
            $table->dropForeign('dispute_tickets_ibfk_4');
        });
    }
};
