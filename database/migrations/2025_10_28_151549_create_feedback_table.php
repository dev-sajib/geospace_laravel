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
        Schema::create('feedback', function (Blueprint $table) {
            $table->integer('feedback_id', true);
            $table->integer('contract_id')->unique('unique_feedback_per_contract');
            $table->integer('project_id')->index('fk_feedback_project');
            $table->integer('company_id')->index('fk_feedback_company');
            $table->integer('freelancer_id')->index('fk_feedback_freelancer');
            $table->integer('attendance_rating');
            $table->text('attendance_comment')->nullable();
            $table->integer('work_quality_rating');
            $table->text('work_quality_comment')->nullable();
            $table->integer('execution_speed_rating');
            $table->text('execution_speed_comment')->nullable();
            $table->integer('adaptability_rating');
            $table->text('adaptability_comment')->nullable();
            $table->integer('general_feedback_rating');
            $table->text('general_feedback_comment')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
