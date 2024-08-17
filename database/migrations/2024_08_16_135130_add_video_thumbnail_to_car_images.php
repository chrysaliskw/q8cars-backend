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
        Schema::table('car_images', function (Blueprint $table) {
            $table->string('thumbnail')->nullable();
            $table->string('video_title')->nullable();
            $table->text('video_description')->nullable();
            $table->bigInteger('video_view_count')->default(0);
            $table->date('video_posted_date')->nullable();
            $table->string('video_posted_media')->nullable();
            $table->string('video_posted_media_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_images', function (Blueprint $table) {
            //
        });
    }
};
