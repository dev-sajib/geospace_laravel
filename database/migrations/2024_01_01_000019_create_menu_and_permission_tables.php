<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->integer('menu_id')->autoIncrement();
            $table->integer('parent_menu_id')->nullable();
            $table->string('menu_name', 100);
            $table->string('menu_url', 500)->nullable();
            $table->string('menu_icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('parent_menu_id')
                  ->references('menu_id')
                  ->on('menu_items')
                  ->onDelete('cascade');
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->autoIncrement();
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->boolean('can_view')->default(1);
            $table->boolean('can_create')->default(0);
            $table->boolean('can_edit')->default(0);
            $table->boolean('can_delete')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('role_id')
                  ->references('role_id')
                  ->on('roles')
                  ->onDelete('cascade');
            
            $table->foreign('menu_id')
                  ->references('menu_id')
                  ->on('menu_items')
                  ->onDelete('cascade');
            
            $table->unique(['role_id', 'menu_id'], 'unique_role_menu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('menu_items');
    }
};
