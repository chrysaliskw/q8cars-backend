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
        Schema::create('offer_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('car_id')->nullable();
            $table->bigInteger('car_version_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->tinyInteger('type');
            $table->bigInteger('offer_id')->nullable();
            $table->string('full_name');
            $table->string('phone_code', 6);
            $table->string('mobile', 20);
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_requests');
    }
};
