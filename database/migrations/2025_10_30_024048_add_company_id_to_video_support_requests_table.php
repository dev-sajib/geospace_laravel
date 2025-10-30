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
        Schema::table('video_support_requests', function (Blueprint $table) {
            // Make freelancer_id nullable
            $table->integer('freelancer_id')->nullable()->change();

            // Add company_id column
            $table->integer('company_id')->nullable()->after('freelancer_id')->index('idx_company_id');
        });

        // Add check constraint after column is created
        DB::statement('ALTER TABLE video_support_requests ADD CONSTRAINT chk_user_type CHECK (freelancer_id IS NOT NULL OR company_id IS NOT NULL)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('video_support_requests', function (Blueprint $table) {
            // Drop check constraint
            DB::statement('ALTER TABLE video_support_requests DROP CONSTRAINT chk_user_type');

            // Drop company_id column
            $table->dropColumn('company_id');

            // Make freelancer_id non-nullable again
            $table->integer('freelancer_id')->nullable(false)->change();
        });
    }
};
