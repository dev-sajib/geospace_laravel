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
        Schema::create('video_support_requests', function (Blueprint $table) {
            $table->integer('request_id', true);
            $table->integer('freelancer_id')->index('idx_freelancer_id');
            $table->date('meeting_date')->index('idx_meeting_date');
            $table->time('meeting_time');
            $table->string('video_link')->nullable();
            $table->enum('status', ['Open', 'Scheduled', 'Completed', 'Cancelled'])->nullable()->default('Open')->index('idx_status');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_support_requests');
    }
};
