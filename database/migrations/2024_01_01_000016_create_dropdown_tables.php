<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dropdown_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 100)->unique();
            $table->text('category_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('dropdown_values', function (Blueprint $table) {
            $table->id('value_id');
            $table->unsignedBigInteger('category_id');
            $table->string('value_name', 255);
            $table->string('value_code', 100)->nullable();
            $table->text('value_description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('dropdown_categories')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dropdown_values');
        Schema::dropIfExists('dropdown_categories');
    }
};
