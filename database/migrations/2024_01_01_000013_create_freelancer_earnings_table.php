<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freelancer_earnings', function (Blueprint $table) {
            $table->integer('earning_id')->autoIncrement();
            $table->integer('freelancer_id');
            $table->decimal('total_earned', 15, 2)->default(0.00);
            $table->decimal('pending_amount', 15, 2)->default(0.00);
            $table->decimal('completed_amount', 15, 2)->default(0.00);
            $table->integer('total_projects')->default(0);
            $table->integer('total_timesheets')->default(0);
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamps();

            $table->foreign('freelancer_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->unique('freelancer_id', 'unique_freelancer');
            $table->index('freelancer_id', 'idx_earnings_freelancer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancer_earnings');
    }
};
