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
            $table->integer('feedback_id')->autoIncrement();
            $table->integer('contract_id');
            $table->integer('project_id');
            $table->integer('company_id');
            $table->integer('freelancer_id');
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
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->unique('contract_id', 'unique_feedback_per_contract');
            $table->index('project_id', 'fk_feedback_project');
            $table->index('company_id', 'fk_feedback_company');
            $table->index('freelancer_id', 'fk_feedback_freelancer');

            $table->foreign('contract_id', 'fk_feedback_contract')
                ->references('contract_id')
                ->on('contracts')
                ->onDelete('cascade');

            $table->foreign('project_id', 'fk_feedback_project')
                ->references('project_id')
                ->on('projects')
                ->onDelete('cascade');

            $table->foreign('company_id', 'fk_feedback_company')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('freelancer_id', 'fk_feedback_freelancer')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });

        // Add check constraints
        DB::statement('ALTER TABLE feedback ADD CONSTRAINT feedback_chk_1 CHECK (attendance_rating BETWEEN 1 AND 5)');
        DB::statement('ALTER TABLE feedback ADD CONSTRAINT feedback_chk_2 CHECK (work_quality_rating BETWEEN 1 AND 5)');
        DB::statement('ALTER TABLE feedback ADD CONSTRAINT feedback_chk_3 CHECK (execution_speed_rating BETWEEN 1 AND 5)');
        DB::statement('ALTER TABLE feedback ADD CONSTRAINT feedback_chk_4 CHECK (adaptability_rating BETWEEN 1 AND 5)');
        DB::statement('ALTER TABLE feedback ADD CONSTRAINT feedback_chk_5 CHECK (general_feedback_rating BETWEEN 1 AND 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
