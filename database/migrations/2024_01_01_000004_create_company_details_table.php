<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->id('company_id');
            $table->unsignedBigInteger('user_id');
            $table->string('company_name', 255);
            $table->string('company_type', 100)->nullable();
            $table->string('industry', 100)->nullable();
            $table->enum('company_size', ['1-10', '11-50', '51-200', '201-500', '500+'])->nullable();
            $table->string('website', 500)->nullable();
            $table->text('description')->nullable();
            $table->integer('founded_year')->nullable();
            $table->string('headquarters', 255)->nullable();
            $table->string('logo', 500)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_details');
    }
};
