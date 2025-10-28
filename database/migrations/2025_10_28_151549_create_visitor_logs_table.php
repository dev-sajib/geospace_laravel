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
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->integer('log_id', true);
            $table->integer('user_id')->nullable()->index('idx_visitor_logs_user_id');
            $table->integer('role_id')->index('idx_visitor_logs_role_id');
            $table->text('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('page_visited', 500)->nullable();
            $table->integer('session_duration')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent()->index('idx_visitor_logs_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
