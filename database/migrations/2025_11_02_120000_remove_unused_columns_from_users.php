<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove unused columns from users table
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove columns that are not being used anywhere
            $table->dropColumn([
                'active_status',    // Duplicate of is_active, not used
                'avatar',           // Not used (profile images in role tables)
                'dark_mode',        // Not used
                'messenger_color'   // Not used
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restore the columns if needed
            $table->tinyInteger('active_status')->default(0);
            $table->string('avatar', 255)->default('avatar.png');
            $table->tinyInteger('dark_mode')->default(0);
            $table->string('messenger_color', 255)->nullable();
        });
    }
};
