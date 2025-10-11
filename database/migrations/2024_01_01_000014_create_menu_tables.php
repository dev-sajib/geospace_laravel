<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id('menu_id');
            $table->unsignedBigInteger('parent_menu_id')->nullable();
            $table->string('menu_name', 100);
            $table->string('menu_url', 255)->nullable();
            $table->string('menu_icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_menu_id')
                  ->references('menu_id')
                  ->on('menu_items')
                  ->onDelete('cascade');
        });

        Schema::create('role_menu_access', function (Blueprint $table) {
            $table->id('access_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('menu_id');
            $table->boolean('can_view')->default(true);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();

            $table->foreign('role_id')
                  ->references('role_id')
                  ->on('roles')
                  ->onDelete('cascade');
            
            $table->foreign('menu_id')
                  ->references('menu_id')
                  ->on('menu_items')
                  ->onDelete('cascade');
            
            $table->unique(['role_id', 'menu_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_menu_access');
        Schema::dropIfExists('menu_items');
    }
};
