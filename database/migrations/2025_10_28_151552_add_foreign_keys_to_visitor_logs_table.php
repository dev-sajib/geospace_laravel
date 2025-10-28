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
        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->foreign(['user_id'], 'visitor_logs_ibfk_1')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['role_id'], 'visitor_logs_ibfk_2')->references(['role_id'])->on('roles')->onUpdate('no action')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->dropForeign('visitor_logs_ibfk_1');
            $table->dropForeign('visitor_logs_ibfk_2');
        });
    }
};
