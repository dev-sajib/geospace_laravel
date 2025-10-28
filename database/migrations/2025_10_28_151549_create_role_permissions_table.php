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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('permission_id', true);
            $table->integer('role_id');
            $table->integer('menu_id')->index('menu_id');
            $table->boolean('can_view')->nullable()->default(true);
            $table->boolean('can_create')->nullable()->default(false);
            $table->boolean('can_edit')->nullable()->default(false);
            $table->boolean('can_delete')->nullable()->default(false);
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->unique(['role_id', 'menu_id'], 'unique_role_menu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
