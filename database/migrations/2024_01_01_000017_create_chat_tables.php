<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->integer('session_id')->autoIncrement();
            $table->integer('user_id');
            $table->integer('support_agent_id')->nullable();
            $table->enum('status', ['Active', 'Closed', 'Waiting'])->default('Waiting');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('support_agent_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->integer('message_id')->autoIncrement();
            $table->integer('session_id');
            $table->integer('sender_id');
            $table->text('message_text');
            $table->enum('message_type', ['Text', 'Image', 'File'])->default('Text');
            $table->string('attachment_url', 500)->nullable();
            $table->boolean('is_from_agent')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('session_id')
                  ->references('session_id')
                  ->on('chat_sessions')
                  ->onDelete('cascade');
            
            $table->foreign('sender_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
