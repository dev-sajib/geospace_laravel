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
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->morphs('participant'); // participant_id, participant_type (User, Admin, etc.)
            $table->enum('role', ['customer', 'support_agent'])->default('customer');
            $table->timestamp('last_read_at')->nullable();
            $table->integer('unread_count')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->unique(['conversation_id', 'participant_id', 'participant_type'], 'conv_part_unique');
            $table->index(['participant_id', 'participant_type']);
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