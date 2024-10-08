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
           $table->bigInteger('body_type')->after('brand_id')->nullable();
           $table->bigInteger('view_count')->after('car_version_2_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_comparison_lists', function (Blueprint $table) {
            //
        });
    }
};
