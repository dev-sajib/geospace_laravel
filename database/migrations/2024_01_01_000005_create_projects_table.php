<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_id');
            $table->unsignedBigInteger('company_id');
            $table->string('project_title', 255);
            $table->text('project_description');
            $table->string('project_category', 100)->nullable();
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('location', 255)->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Completed', 'Cancelled'])->default('Open');
            $table->text('required_skills')->nullable();
            $table->enum('experience_level', ['Entry', 'Intermediate', 'Expert'])->nullable();
            $table->integer('estimated_duration')->nullable();
            $table->string('duration_unit', 20)->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
