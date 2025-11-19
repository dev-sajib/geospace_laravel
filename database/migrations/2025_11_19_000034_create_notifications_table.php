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
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('notification_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('title', 255);
            $table->text('message');
            $table->enum('type', ['Info', 'Success', 'Warning', 'Error'])->default('Info');
            $table->string('action_url', 500)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('user_id', 'idx_notifications_user_id');
            $table->index('is_read', 'idx_notifications_is_read');
            $table->index('created_at', 'idx_notifications_created_at');

            $table->foreign('user_id', 'notifications_ibfk_1')
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
        Schema::dropIfExists('notifications');
    }
};
