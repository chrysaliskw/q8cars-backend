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
            $table->string('electronic_multi_tripmeter')->nullable();
            $table->string('tachometer')->nullable();
            $table->string('digital_odometer')->nullable();
            $table->string('LED_Taillights')->nullable();
            $table->string('LED_DRLs')->nullable();
            $table->string('Halogen_Headlamps')->nullable();
            $table->string('LED_Headlights')->nullable();
            $table->string('child_safety_locks')->nullable();
            $table->string('apple_car_play')->nullable();
            $table->string('touch_screen')->nullable();
            $table->string('speakers_rear')->nullable();
            $table->string('speakers_front')->nullable();
            $table->string('radio')->nullable();
            $table->string('android_auto')->nullable();
            $table->string('digital_clock')->nullable();
            $table->string('bluetooth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_versions', function (Blueprint $table) {
            //
        });
    }
};
