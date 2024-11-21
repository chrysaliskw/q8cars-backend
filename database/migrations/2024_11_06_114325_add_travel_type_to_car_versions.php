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
        Schema::table('car_versions', function (Blueprint $table) {
            $table->tinyInteger('travel_type')->nullable()->after('transmission_type');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_versions', function (Blueprint $table) {
            $table->dropColumn('travel_type');
        });
    }
};
