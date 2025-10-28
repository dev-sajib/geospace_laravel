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
        Schema::create('timesheet_day_comments', function (Blueprint $table) {
            $table->integer('comment_id', true);
            $table->integer('day_id')->index('idx_comments_day');
            $table->integer('timesheet_id')->index('idx_comments_timesheet');
            $table->integer('comment_by')->index('comment_by');
            $table->enum('comment_type', ['Company', 'Freelancer'])->index('idx_comments_type');
            $table->text('comment_text');
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet_day_comments');
    }
};
