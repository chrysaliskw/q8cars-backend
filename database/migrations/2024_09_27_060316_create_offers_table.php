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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_version_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('key_feature_1')->nullable();
            $table->string('key_icon_1')->nullable();
            $table->string('key_feature_2')->nullable();
            $table->string('key_icon_2')->nullable();
            $table->text('description');
            $table->text('html_description')->nullable();
            $table->string('offer');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(1);
            $table->integer('view_count')->default(0);
            $table->integer('show_in_suggestions')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
