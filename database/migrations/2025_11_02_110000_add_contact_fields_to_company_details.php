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
        Schema::table('company_details', function (Blueprint $table) {
            $table->string('contact_first_name', 100)->nullable()->after('company_name');
            $table->string('contact_last_name', 100)->nullable()->after('contact_first_name');
            $table->string('contact_phone', 20)->nullable()->after('contact_last_name');
            $table->text('address')->nullable()->after('headquarters');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('postal_code', 20)->nullable()->after('state');
            $table->string('country', 100)->nullable()->after('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->dropColumn([
                'contact_first_name',
                'contact_last_name',
                'contact_phone',
                'address',
                'city',
                'state',
                'postal_code',
                'country'
            ]);
        });
    }
};
