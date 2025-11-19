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
        Schema::create('certifications', function (Blueprint $table) {
            $table->integer('certification_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('certification_name', 255);
            $table->string('issuing_organization', 255);
            $table->date('issue_date');
            $table->date('expiration_date')->nullable();
            $table->string('credential_id', 255)->nullable();
            $table->string('credential_url', 500)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('user_id', 'idx_certifications_user');

            $table->foreign('user_id', 'certifications_ibfk_1')
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
        Schema::dropIfExists('certifications');
    }
};
