<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('video_support_requests', function (Blueprint $table) {
            $table->integer('request_id')->autoIncrement();
            $table->integer('freelancer_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->date('meeting_date');
            $table->time('meeting_time');
            $table->string('video_link', 255)->nullable();
            $table->enum('status', ['Open', 'Scheduled', 'Completed', 'Cancelled'])->default('Open');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

            $table->index('freelancer_id', 'idx_freelancer_id');
            $table->index('company_id', 'idx_company_id');
            $table->index('status', 'idx_status');
            $table->index('meeting_date', 'idx_meeting_date');

            $table->foreign('freelancer_id', 'video_support_requests_ibfk_1')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });

        // Add check constraint
        DB::statement('ALTER TABLE video_support_requests ADD CONSTRAINT chk_user_type CHECK ((freelancer_id IS NOT NULL) OR (company_id IS NOT NULL))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_support_requests');
    }
};
