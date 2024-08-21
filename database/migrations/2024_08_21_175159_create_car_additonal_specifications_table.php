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
        Schema::create('car_additonal_specifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('car_id')->index();
            $table->bigInteger('car_version_id')->index()->nullable();
            $table->tinyInteger('category_id')->index();
            $table->tinyInteger('input_type')->default(1);
            $table->string('specification');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_additonal_specifications');
    }
};
