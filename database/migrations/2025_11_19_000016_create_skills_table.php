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
            $table->integer('skill_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('skill_name', 255);
            $table->enum('proficiency_level', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])->default('Intermediate');
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('user_id', 'idx_skills_user');

            $table->foreign('user_id', 'skills_ibfk_1')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
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
