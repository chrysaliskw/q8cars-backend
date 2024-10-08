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
        Schema::table('car_comparison_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->index()->nullable()->after('page');
            $table->unsignedBigInteger('brand_1_id')->index()->nullable()->after('car_id');
            $table->unsignedBigInteger('brand_2_id')->index()->nullable()->after('car_version_1_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_comparison_lists', function (Blueprint $table) {
            $table->dropColumn(['brand_id', 'brand_1_id', 'brand_2_id']);
        });
    }
};
