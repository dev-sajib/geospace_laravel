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
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreign(['project_id'], 'contracts_ibfk_1')->references(['project_id'])->on('projects')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['freelancer_id'], 'contracts_ibfk_2')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['company_id'], 'contracts_ibfk_3')->references(['company_id'])->on('company_details')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign('contracts_ibfk_1');
            $table->dropForeign('contracts_ibfk_2');
            $table->dropForeign('contracts_ibfk_3');
        });
    }
};
