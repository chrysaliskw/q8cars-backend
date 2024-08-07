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
        Schema::create('test_drives', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('car_id')->index();
            $table->bigInteger('user_id')->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_code', 6);
            $table->string('mobile', 20);
            $table->string('otp', '6')->nullable();
            $table->dateTime('otp_expiry')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_drives');
    }
};
