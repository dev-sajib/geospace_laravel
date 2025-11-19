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
        Schema::create('company_details', function (Blueprint $table) {
            $table->integer('company_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('company_name', 255);
            $table->string('contact_first_name', 100)->nullable();
            $table->string('contact_last_name', 100)->nullable();
            $table->string('contact_designation', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('company_type', 100)->nullable();
            $table->string('industry', 100)->nullable();
            $table->enum('company_size', ['1-10', '11-50', '51-200', '201-500', '500+'])->nullable();
            $table->string('website', 500)->nullable();
            $table->string('contact_linkedin', 500)->nullable();
            $table->text('description')->nullable();
            $table->integer('founded_year')->nullable();
            $table->string('headquarters', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('logo', 500)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('user_id');

            $table->foreign('user_id', 'company_details_ibfk_1')
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
        Schema::dropIfExists('company_details');
    }
};
