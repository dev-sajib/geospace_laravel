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
            $table->integer('file_id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path', 500);
            $table->integer('file_size');
            $table->string('mime_type', 100);
            $table->enum('file_category', ['Profile', 'Resume', 'Project', 'Invoice', 'Other'])->nullable()->default('Other');
            $table->string('entity_type', 100)->nullable();
            $table->integer('entity_id')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
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
