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
        Schema::table('feedback', function (Blueprint $table) {
            $table->foreign(['company_id'], 'fk_feedback_company')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['contract_id'], 'fk_feedback_contract')->references(['contract_id'])->on('contracts')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['freelancer_id'], 'fk_feedback_freelancer')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['project_id'], 'fk_feedback_project')->references(['project_id'])->on('projects')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropForeign('fk_feedback_company');
            $table->dropForeign('fk_feedback_contract');
            $table->dropForeign('fk_feedback_freelancer');
            $table->dropForeign('fk_feedback_project');
        });
    }
};
