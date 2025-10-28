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
            $table->unsignedBigInteger('conversation_id')->index('conversation_participants_conversation_id_foreign');
            $table->string('participant_type');
            $table->unsignedBigInteger('participant_id');
            $table->enum('role', ['customer', 'support_agent'])->default('customer');
            $table->timestamp('last_read_at')->nullable();
            $table->integer('unread_count')->default(0);
            $table->timestamps();

            $table->index(['participant_type', 'participant_id']);
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
