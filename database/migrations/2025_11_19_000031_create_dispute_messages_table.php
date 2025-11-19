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
        Schema::create('dispute_messages', function (Blueprint $table) {
            $table->integer('message_id')->autoIncrement();
            $table->integer('ticket_id');
            $table->integer('sender_id');
            $table->text('message_text');
            $table->string('attachment_url', 500)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('ticket_id');
            $table->index('sender_id');

            $table->foreign('ticket_id', 'dispute_messages_ibfk_1')
                ->references('ticket_id')
                ->on('dispute_tickets')
                ->onDelete('cascade');

            $table->foreign('sender_id', 'dispute_messages_ibfk_2')
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
        Schema::dropIfExists('dispute_messages');
    }
};
