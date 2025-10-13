<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('role_id');
            $table->text('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('page_visited', 500)->nullable();
            $table->integer('session_duration')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes only - no foreign keys for logging tables
            $table->index('user_id');
            $table->index('role_id');
            $table->index('created_at');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 255);
            $table->string('entity_type', 100)->nullable();
            $table->integer('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Index only - no foreign key for logging table
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('visitor_logs');
    }
};