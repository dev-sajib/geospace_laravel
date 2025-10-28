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
            $table->integer('notification_id', true);
            $table->integer('user_id')->index('idx_notifications_user_id');
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['Info', 'Success', 'Warning', 'Error'])->nullable()->default('Info');
            $table->string('action_url', 500)->nullable();
            $table->boolean('is_read')->nullable()->default(false)->index('idx_notifications_is_read');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent()->index('idx_notifications_created_at');
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
