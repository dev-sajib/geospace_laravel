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
        Schema::create('expertise', function (Blueprint $table) {
            $table->integer('expertise_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('expertise_name', 255);
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index('user_id', 'idx_expertise_user');

            $table->foreign('user_id', 'expertise_ibfk_1')
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
        Schema::dropIfExists('expertise');
    }
};
