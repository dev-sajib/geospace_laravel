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
        Schema::table('dispute_messages', function (Blueprint $table) {
            $table->foreign(['ticket_id'], 'dispute_messages_ibfk_1')->references(['ticket_id'])->on('dispute_tickets')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['sender_id'], 'dispute_messages_ibfk_2')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispute_messages', function (Blueprint $table) {
            $table->dropForeign('dispute_messages_ibfk_1');
            $table->dropForeign('dispute_messages_ibfk_2');
        });
    }
};
