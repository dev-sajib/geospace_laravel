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
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->integer('file_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('original_filename', 255);
            $table->string('stored_filename', 255);
            $table->string('file_path', 500);
            $table->integer('file_size');
            $table->string('mime_type', 100);
            $table->enum('file_category', ['Profile', 'Resume', 'Project', 'Invoice', 'Other'])->default('Other');
            $table->string('entity_type', 100)->nullable();
            $table->integer('entity_id')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('user_id');

            $table->foreign('user_id', 'file_uploads_ibfk_1')
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
        Schema::dropIfExists('file_uploads');
    }
};
