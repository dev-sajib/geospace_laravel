<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id')->autoIncrement();
            $table->string('email', 255)->unique();
            $table->string('password_hash', 255);
            $table->integer('role_id');
            $table->string('user_position', 100)->nullable();
            $table->string('auth_provider', 50)->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_verified')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();

            $table->foreign('role_id')
                  ->references('role_id')
                  ->on('roles')
                  ->onDelete('restrict');
            
            $table->index('email', 'idx_users_email');
            $table->index('role_id', 'idx_users_role_id');
            $table->index('is_active', 'idx_users_is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
