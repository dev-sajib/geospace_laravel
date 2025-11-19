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
        Schema::create('portfolio', function (Blueprint $table) {
            $table->integer('portfolio_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('image_url', 500);
            $table->string('project_url', 500)->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('user_id', 'idx_portfolio_user');

            $table->foreign('user_id', 'portfolio_ibfk_1')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio');
    }
};
