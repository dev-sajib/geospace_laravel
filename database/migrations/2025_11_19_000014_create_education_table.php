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
        Schema::create('education', function (Blueprint $table) {
            $table->integer('education_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('institution_name', 255);
            $table->string('degree', 255);
            $table->string('field_of_study', 255)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->string('grade', 50)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('user_id', 'idx_education_user');

            $table->foreign('user_id', 'education_ibfk_1')
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
        Schema::dropIfExists('education');
    }
};
