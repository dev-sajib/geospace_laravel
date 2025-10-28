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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('email')->unique('email');
            $table->string('password_hash');
            $table->integer('role_id')->index('idx_users_role_id');
            $table->string('user_position', 100)->nullable();
            $table->string('auth_provider', 50)->nullable();
            $table->boolean('is_active')->nullable()->default(true)->index('idx_users_is_active');
            $table->boolean('is_verified')->nullable()->default(false);
            $table->enum('verification_status', ['pending', 'awaiting', 'verified', 'rejected'])->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->boolean('active_status')->default(false);
            $table->string('avatar')->default('avatar.png');
            $table->boolean('dark_mode')->default(false);
            $table->string('messenger_color')->nullable();

            $table->index(['email'], 'idx_users_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
