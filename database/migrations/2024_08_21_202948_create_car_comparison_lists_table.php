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
        Schema::create('car_comparison_lists', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('page')->default(1);
            $table->bigInteger('car_1_id')->index();
            $table->bigInteger('car_version_1_id')->index()->nullable();
            $table->bigInteger('car_2_id')->index();
            $table->bigInteger('car_version_2_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_comparison_lists');
    }
};
