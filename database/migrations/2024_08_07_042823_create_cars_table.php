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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->bigInteger('brand_id')->index();
            $table->string('image');
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
            $table->array('fuel_types');
            $table->array('transmission_type');
            $table->array('colours');
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
            $table->float('avg_rating')->default(0);
            $table->integer('rating_1')->default(0);
            $table->integer('rating_2')->default(0);
            $table->integer('rating_3')->default(0);
            $table->integer('rating_4')->default(0);
            $table->integer('rating_5')->default(0);
            $table->integer('total_reviews_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
