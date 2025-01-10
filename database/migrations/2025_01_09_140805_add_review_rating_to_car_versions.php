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
            $table->float('avg_rating')->default(0);
            $table->integer('total_reviews_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_car_versions', function (Blueprint $table) {
            //
        });
    }
};
