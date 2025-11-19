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
            $table->integer('log_id')->autoIncrement();
            $table->integer('user_id')->nullable();
            $table->integer('role_id');
            $table->text('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('page_visited', 500)->nullable();
            $table->integer('session_duration')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('user_id', 'idx_visitor_logs_user_id');
            $table->index('role_id', 'idx_visitor_logs_role_id');
            $table->index('created_at', 'idx_visitor_logs_created_at');

            $table->foreign('user_id', 'visitor_logs_ibfk_1')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('role_id', 'visitor_logs_ibfk_2')
                ->references('role_id')
                ->on('roles')
                ->onDelete('restrict');
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
