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
        Schema::create('projects', function (Blueprint $table) {
            $table->integer('project_id', true);
            $table->integer('company_id')->index('company_id');
            $table->string('project_title');
            $table->text('project_description');
            $table->string('project_type', 100)->nullable();
            $table->decimal('budget', 12)->nullable();
            $table->string('currency', 3)->nullable()->default('CAD');
            $table->integer('duration_weeks')->nullable();
            $table->enum('status', ['Draft', 'Published', 'In Progress', 'Completed', 'Cancelled'])->nullable()->default('Draft');
            $table->json('skills_required')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_remote')->nullable()->default(false);
            $table->date('deadline')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
