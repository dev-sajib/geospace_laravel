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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->integer('menu_id', true);
            $table->integer('parent_menu_id')->nullable()->index('parent_menu_id');
            $table->string('menu_name', 100);
            $table->string('menu_url', 500)->nullable();
            $table->string('menu_icon', 100)->nullable();
            $table->integer('sort_order')->nullable()->default(0);
            $table->boolean('is_active')->nullable()->default(true);
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
