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
        Schema::create('user_details', function (Blueprint $table) {
            $table->integer('user_details_id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('designation')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('profile_image', 500)->nullable();
            $table->text('bio')->nullable();
            $table->text('summary')->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->string('resume_or_cv', 500)->nullable();
            $table->decimal('hourly_rate', 10)->nullable();
            $table->enum('availability_status', ['Available', 'Busy', 'Unavailable'])->nullable()->default('Available');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
