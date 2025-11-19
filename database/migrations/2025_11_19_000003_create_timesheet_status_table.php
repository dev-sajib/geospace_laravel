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
        Schema::create('timesheet_status', function (Blueprint $table) {
            $table->integer('status_id')->autoIncrement();
            $table->string('status_name', 50)->unique();
            $table->text('status_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet_status');
    }
};
