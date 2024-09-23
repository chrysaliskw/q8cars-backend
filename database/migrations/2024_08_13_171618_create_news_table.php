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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('car_id');
            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('car_version_id')->nullable();
            $table->string('title'); 
            $table->longText('content');
            $table->string('media_name'); 
            $table->string('media_logo'); 
            $table->longText('html_content');
            $table->string('image'); 
            $table->dateTime('posted_time'); 
            $table->tinyInteger('is_published')->default(1); 
            $table->tinyInteger('status'); 
            $table->string('read_time'); 
            $table->date('expiry_date');
            $table->tinyInteger('show_in_detail_page')->default(2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
