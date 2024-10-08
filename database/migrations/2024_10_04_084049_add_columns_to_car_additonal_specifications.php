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
        Schema::table('car_additonal_specifications', function (Blueprint $table) {
            $table->tinyInteger('is_key_feature')->after('unit')->default(0);
            $table->tinyInteger('is_key_spec')->after('is_key_feature')->default(0);
            $table->string('key_icon')->after('is_key_spec')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_additonal_specifications', function (Blueprint $table) {
            //
        });
    }
};
