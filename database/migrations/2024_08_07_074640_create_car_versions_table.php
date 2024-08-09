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
        Schema::create('car_versions', function (Blueprint $table) {
            $table->id();
            $table->string('varient_name');
            $table->decimal('ex_showroom_price', 10,2);
            $table->decimal('on_road_price', 10,2);
            $table->decimal('finance_available', 10,2);
            $table->decimal('service_charge', 10,2);
            $table->decimal('insurance', 10,2);
            $table->tinyInteger('air_condition')->default(1);
            $table->float('length');
            $table->float('width');
            $table->float('height');
            $table->float('boot_space')->nullable();
            $table->string('power_windows')->nullable();
            $table->float('fuel_tank_capacity')->nullable();
            $table->string('seat_upholstery')->nullable();
            $table->json('colours');
            $table->float('engine_capacity');
            $table->float('power');
            $table->float('torque');
            $table->string('drive_train')->nullable();
            $table->string('acceleration')->nullable();
            $table->string('top_speed')->nullable();
            $table->integer('seat_capacity');
            $table->float('mileage')->nullable();
            $table->tinyInteger('profession')->nullable();
            $table->tinyInteger('safety_ratings')->nullable();
            $table->string('engine_type');
            $table->string('max_torque');
            $table->integer('valves_per_cylinder');
            $table->integer('no_of_cylinders');
            $table->string('bore_stroke');
            $table->string('compression_ratio');
            $table->string('super_charge');
            $table->string('gear_box');
            $table->tinyInteger('fuel_type');
            $table->tinyInteger('transmission_type');
            $table->float('tank_capacity');
            $table->string('emission_norm_complains');
            $table->string('front_suspension');
            $table->string('rear_suspension');
            $table->string('steering_type');
            $table->string('steering_column');
            $table->string('tuning_radius');
            $table->string('front_brake_type');
            $table->string('rear_brake_type');
            $table->string('alloy_wheel_front');
            $table->string('alloy_wheel_rear');
            $table->string('power_steering');
            $table->string('air_conditioner');
            $table->string('usb_charger');
            $table->string('automatic_headlamps');
            $table->string('adjustable_headlamps');
            $table->string('wheel_covers');
            $table->string('alloy_wheels');
            $table->string('integrated_antenna');
            $table->string('sun_roof');
            $table->string('tyre_size');
            $table->string('anti_brake_system');
            $table->string('anti_theft_alarm');
            $table->string('no_of_airbags');
            $table->string('passenger_airbags');
            $table->string('driver_airbags');
            $table->string('360_view_camera');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_versions');
    }
};
