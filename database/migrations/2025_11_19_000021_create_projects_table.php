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
            $table->integer('project_id')->autoIncrement();
            $table->integer('company_id');
            $table->string('project_title', 255);
            $table->text('project_description');
            $table->string('project_type', 100)->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('currency', 3)->default('CAD');
            $table->integer('duration_weeks')->nullable();
            $table->enum('status', ['Draft', 'Published', 'In Progress', 'Completed', 'Cancelled'])->default('Draft');
            $table->json('skills_required')->nullable();
            $table->string('location', 255)->nullable();
            $table->boolean('is_remote')->default(false);
            $table->date('deadline')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('company_id');

            $table->foreign('company_id', 'projects_ibfk_1')
                ->references('company_id')
                ->on('company_details')
                ->onDelete('cascade');
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
