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
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('conversation_id')->unsigned();
            $table->string('participant_type', 255);
            $table->bigInteger('participant_id')->unsigned();
            $table->enum('role', ['customer', 'support_agent'])->default('customer');
            $table->timestamp('last_read_at')->nullable();
            $table->integer('unread_count')->default(0);
            $table->timestamps();

            $table->index(['participant_type', 'participant_id'], 'conversation_participants_participant_type_participant_id_index');

            $table->foreign('conversation_id', 'conversation_participants_conversation_id_foreign')
                ->references('id')
                ->on('conversations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_participants');
    }
};
