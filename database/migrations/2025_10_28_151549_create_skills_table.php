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
        Schema::create('skills', function (Blueprint $table) {
            $table->integer('skill_id', true);
            $table->integer('user_id')->index('idx_skills_user');
            $table->string('skill_name');
            $table->enum('proficiency_level', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])->nullable()->default('Intermediate');
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
