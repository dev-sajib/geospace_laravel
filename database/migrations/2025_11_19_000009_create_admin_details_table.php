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
        Schema::create('admin_details', function (Blueprint $table) {
            $table->integer('admin_detail_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->unique('user_id', 'admin_details_user_id_unique');
            $table->index('user_id', 'idx_admin_user_id');

            $table->foreign('user_id', 'fk_admin_details_user')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_details');
    }
};
