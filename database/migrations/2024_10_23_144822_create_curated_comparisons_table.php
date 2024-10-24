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
        Schema::create('curated_comparisons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('brand_id_1');
            $table->bigInteger('car_id_1');
            $table->bigInteger('brand_id_2')->nullable();
            $table->bigInteger('car_id_2')->nullable();
            $table->bigInteger('brand_id_3')->nullable();
            $table->bigInteger('car_id_3')->nullable();
            $table->string('title');
            $table->text('content');
            $table->text('html_content');
            $table->string('source');
            $table->string('image_1');
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->date('published_date')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curated_comparisons');
    }
};
