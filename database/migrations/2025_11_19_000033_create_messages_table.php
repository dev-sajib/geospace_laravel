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
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('conversation_id')->unsigned();
            $table->string('sender_type', 255);
            $table->bigInteger('sender_id')->unsigned();
            $table->text('content');
            $table->string('attachment_path', 255)->nullable();
            $table->string('attachment_name', 255)->nullable();
            $table->enum('message_type', ['text', 'image', 'file'])->default('text');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['sender_type', 'sender_id'], 'messages_sender_type_sender_id_index');
            $table->index(['conversation_id', 'created_at'], 'messages_conversation_id_created_at_index');
            $table->index(['sender_id', 'sender_type'], 'messages_sender_id_sender_type_index');

            $table->foreign('conversation_id', 'messages_conversation_id_foreign')
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
        Schema::dropIfExists('messages');
    }
};
