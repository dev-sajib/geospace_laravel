<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->integer('project_id')->autoIncrement();
            $table->integer('company_id');
            $table->string('project_title', 255);
            $table->text('project_description');
            $table->string('project_type', 100)->nullable();
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->string('currency', 3)->default('CAD');
            $table->integer('duration_weeks')->nullable();
            $table->enum('status', ['Draft', 'Published', 'In Progress', 'Completed', 'Cancelled'])->default('Draft');
            $table->json('skills_required')->nullable();
            $table->string('location', 255)->nullable();
            $table->boolean('is_remote')->default(0);
            $table->date('deadline')->nullable();
            $table->timestamps();

            $table->foreign('company_id')
                  ->references('company_id')
                  ->on('company_details')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
