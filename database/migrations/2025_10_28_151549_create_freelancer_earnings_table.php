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
        Schema::create('freelancer_earnings', function (Blueprint $table) {
            $table->integer('earning_id', true);
            $table->integer('freelancer_id')->index('idx_earnings_freelancer');
            $table->decimal('total_earned', 15)->nullable()->default(0);
            $table->decimal('pending_amount', 15)->nullable()->default(0);
            $table->decimal('completed_amount', 15)->nullable()->default(0);
            $table->integer('total_projects')->nullable()->default(0);
            $table->integer('total_timesheets')->nullable()->default(0);
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->unique(['freelancer_id'], 'unique_freelancer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_earnings');
    }
};
