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
        Schema::table('freelancer_earnings', function (Blueprint $table) {
            $table->foreign(['freelancer_id'], 'freelancer_earnings_ibfk_1')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freelancer_earnings', function (Blueprint $table) {
            $table->dropForeign('freelancer_earnings_ibfk_1');
        });
    }
};
